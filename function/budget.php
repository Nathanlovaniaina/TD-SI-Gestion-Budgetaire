<?php
include("dbconnect.php");

function getBudgetDetails($date_debut, $date_fin, $id_departement, $id_categorie) {
    $pdo = connectDB();

    if($id_departement != 0){
        $sql = "
        SELECT 
            Type.id_type,
            Type.libelle AS type_depense,
            Nature.id_nature,
            Nature.nom AS nature,
            Departement.id_departement,
            Departement.nom AS departement,
            Categorie.id_cate,
            Categorie.nom AS categorie,
            COALESCE(SUM(Budget.montant), 0) AS montant
        FROM Type
        JOIN Nature ON Type.id_nature = Nature.id_nature
        JOIN Categorie ON Type.id_cate = Categorie.id_cate
        JOIN Departement ON Nature.id_departement = Departement.id_departement
        LEFT JOIN Budget ON Type.id_type = Budget.id_type 
            AND Budget.date_budget BETWEEN :date_debut AND :date_fin
            AND Budget.id_departement = Departement.id_departement
        WHERE Departement.id_departement = :id_departement 
            AND Categorie.id_cate = :id_categorie
        GROUP BY Type.id_type, Type.libelle, Nature.id_nature, Nature.nom, Departement.id_departement, Departement.nom, Categorie.id_cate, Categorie.nom;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':date_debut' => $date_debut, 
        ':date_fin' => $date_fin, 
        ':id_departement' => $id_departement, 
        ':id_categorie' => $id_categorie
    ]);
    }

    if($id_departement ==0){
        $sql = "
        SELECT 
            Type.id_type,
            Type.libelle AS type_depense,
            Nature.id_nature,
            Nature.nom AS nature,
            Departement.id_departement,
            Departement.nom AS departement,
            Categorie.id_cate,
            Categorie.nom AS categorie,
            COALESCE(SUM(Budget.montant), 0) AS montant
        FROM Type
        JOIN Nature ON Type.id_nature = Nature.id_nature
        JOIN Categorie ON Type.id_cate = Categorie.id_cate
        JOIN Departement ON Nature.id_departement = Departement.id_departement
        LEFT JOIN Budget ON Type.id_type = Budget.id_type 
            AND Budget.date_budget BETWEEN :date_debut AND :date_fin
            AND Budget.id_departement = Departement.id_departement
        WHERE Categorie.id_cate = :id_categorie
        GROUP BY Type.id_type, Type.libelle, Nature.id_nature, Nature.nom, Departement.id_departement, Departement.nom, Categorie.id_cate, Categorie.nom;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':date_debut' => $date_debut, 
        ':date_fin' => $date_fin,  
        ':id_categorie' => $id_categorie
    ]);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_bilan_budgetaire_($date_debut, $date_fin, $id_departement){
    $tab = [];
    $data = getBudgetDetails($date_debut, $date_fin, $id_departement, 1);
    $data2 = getBudgetDetails($date_debut, $date_fin, $id_departement, 2);
    
    foreach ($data as $budget) {
        $tab['recettes'][] = [
            'type' => $budget['type_depense'],
            'nature' => $budget['nature'],
            'prevision' => calculPrevision($budget['id_type'], $date_debut),
            'realisation' => $budget['montant'],
            'ecart' => calculecart(calculPrevision($budget['id_type'], $date_debut), $budget['montant'])
        ];
    }
    
    foreach ($data2 as $budget) {
        $tab['depenses'][] = [
            'type' => $budget['type_depense'],
            'nature' => $budget['nature'],
            'prevision' => calculPrevision($budget['id_type'], $date_debut),
            'realisation' => $budget['montant'],
            'ecart' => calculecart(calculPrevision($budget['id_type'], $date_debut), $budget['montant'])
        ];
    }
    
    $total_recett = calcul_total($data, $date_debut, $date_fin);
    $total_depense = calcul_total($data2, $date_debut, $date_fin);
    
    $tab['totaux'] = [
        'total_recettes' => [
            'prevision' => $total_recett[1],
            'realisation' => $total_recett[0],
            'ecart' => $total_recett[2]
        ],
        'total_depenses' => [
            'prevision' => $total_depense[1],
            'realisation' => $total_depense[0],
            'ecart' => $total_depense[2]
        ],
        'resultat' => [
            'prevision' => $total_recett[1] - $total_depense[1],
            'realisation' => $total_recett[0] - $total_depense[0],
            'ecart' => $total_recett[2] - $total_depense[2]
        ]
    ];
    
    return $tab;
}



function calculPrevision(int $id_type, $current_date)
{
    $pdo = connectDB();

    // 1. Tentative de calcul par taux de croissance (méthode prioritaire)
    $stmtHistorique = $pdo->prepare("
        SELECT montant, date_budget 
        FROM Budget 
        WHERE id_type = :id_type AND date_budget <= :current_date
        ORDER BY date_budget ASC
    ");
    $stmtHistorique->execute([':id_type' => $id_type , ':current_date' => $current_date]);
    $historique = $stmtHistorique->fetchAll(PDO::FETCH_ASSOC);

    if (count($historique) >= 2) {
        $initial = (float) $historique[0]['montant'];
        $final = (float) $historique[count($historique) - 1]['montant'];

        // Éviter la division par zéro
        if ($initial != 0) {
            $taux_croissance = ($final - $initial) / $initial;
            $prevision = $final * (1 + $taux_croissance);
            return round($prevision, 2);
        }
    }

    // 3. Fallback sur les prévisions existantes
    $sqlPrevision = "SELECT montant FROM Prevision WHERE id_type = :id_type ORDER BY date_prevision DESC LIMIT 1";
    $stmtPrevision = $pdo->prepare($sqlPrevision);
    $stmtPrevision->execute([':id_type' => $id_type]);
    $rowPrevision = $stmtPrevision->fetch(PDO::FETCH_ASSOC);

    if ($rowPrevision) {
        return (float) $rowPrevision['montant'];
    }

    // 4. Valeur par défaut si aucune donnée
    return 0.0;
}

function calculecart($prevision, $realisation)
{
    return $realisation - $prevision;
}

function calcul_total($tab,$date_debut,$date_fin){
    $total_realiser = 0;
    $total_prevision = 0;
    $total_ecart = 0;
    foreach ($tab as $budget) {
        $total_realiser += $budget['montant'];
        $total_prevision += calculPrevision($budget['id_type'],$date_debut);
        $total_ecart += calculecart(calculPrevision($budget['id_type'],$date_debut),$budget['montant']);
    }
    return array($total_realiser,$total_prevision,$total_ecart);
}

function estProprietaire($id_departement_proprietaire, $id_departement_possession) {
    $pdo = connectDB(); 

    $sql = "
        SELECT COUNT(*) 
        FROM Autorisation 
        WHERE id_departement_proprietaire = :id_proprietaire 
        AND id_departement_possession = :id_possession
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_proprietaire' => $id_departement_proprietaire,
        ':id_possession' => $id_departement_possession
    ]);

    return ($stmt->fetchColumn() > 0) ? true:false; 
}

function getDepartementById(int $id_departement): ?array
{
    // Connexion à la base de données
    $pdo = connectDB();

    // Requête SQL pour récupérer le département par son ID
    $sql = "SELECT id_departement, nom 
            FROM Departement 
            WHERE id_departement = :id_departement";
    
    // Préparation et exécution de la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_departement' => $id_departement]);

    // Récupération du résultat sous forme de tableau associatif
    $departement = $stmt->fetch(PDO::FETCH_ASSOC);

    // Retourne le département ou null si non trouvé
    return $departement ?: null;
}

function getTypesByDepartement($id_departement) {
    // Connexion à la base de données
    $pdo = connectDB();

    // Requête SQL pour récupérer les types de dépenses d'un département
    $sql = "
        SELECT 
            Type.id_type,
            Type.libelle AS type_depense,
            Nature.id_nature,
            Nature.nom AS nature,
            Departement.id_departement,
            Departement.nom AS departement,
            Categorie.id_cate,
            Categorie.nom AS categorie
        FROM Type
        JOIN Nature ON Type.id_nature = Nature.id_nature
        JOIN Categorie ON Type.id_cate = Categorie.id_cate
        JOIN Departement ON Nature.id_departement = Departement.id_departement
        WHERE Departement.id_departement = :id_departement;
    ";

    // Préparation et exécution de la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_departement' => $id_departement
    ]);

    // Retourne les résultats sous forme de tableau associatif
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertBudget($montant, $date_budget, $id_departement, $id_type, $description) {
    // Connexion à la base de données
    $pdo = connectDB();

    // Requête SQL pour insérer un nouveau budget
    $sql = "
        INSERT INTO Budget (montant, date_budget, id_departement, id_type, description)
        VALUES (:montant, :date_budget, :id_departement, :id_type, :description);
    ";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Exécution de la requête avec les paramètres
    try {
        $stmt->execute([
            ':montant' => $montant,
            ':date_budget' => $date_budget,
            ':id_departement' => $id_departement,
            ':id_type' => $id_type,
            ':description' => $description
        ]);

        // Retourne l'ID du budget inséré
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        // En cas d'erreur, retourne false
        error_log("Erreur lors de l'insertion du budget : " . $e->getMessage());
        return false;
    }
}

function importCSV($filePath) {
    // Vérifier si le fichier existe
    if (!file_exists($filePath) || !is_readable($filePath)) {
        return "Erreur : Fichier non trouvé ou illisible.";
    }

    // Ouvrir le fichier en mode lecture
    $handle = fopen($filePath, "r");
    if ($handle === false) {
        return "Erreur : Impossible d'ouvrir le fichier.";
    }

    // Ignorer la première ligne (en-têtes)
    fgetcsv($handle);

    $insertedRows = 0;

    // Lire chaque ligne du fichier
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        // Vérifier que toutes les colonnes sont présentes
        if (count($data) < 5) {
            continue;
        }

        // Récupérer les valeurs depuis le CSV
        list($montant, $date_budget, $id_departement, $id_type, $description) = $data;

        // Appeler la fonction d'insertion
        if (insertBudget($montant, $date_budget, $id_departement, $id_type, $description)) {
            $insertedRows++;
        }
    }

    // Fermer le fichier
    fclose($handle);

    return "Importation terminée : $insertedRows lignes insérées.";
}

function calculerSolde($id_departement, $debut, $fin) {
    $conn = connectDB();
    
    // Calcul des recettes (id_cate = 1 pour les recettes)
    $sql_recettes = "SELECT SUM(b.montant) AS total_recettes 
                     FROM budget b 
                     JOIN type t ON b.id_type = t.id_type 
                     WHERE b.id_departement = :id_departement
                     AND t.id_cate = 1 
                     AND b.date_budget <= :debut";
    $stmt = $conn->prepare($sql_recettes);
    $stmt->execute([
        ':id_departement' => $id_departement,
        ':debut' => $debut,
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_recettes = $result['total_recettes'] ?? 0;

    // Calcul des dépenses (id_cate = 2 pour les dépenses)
    $sql_depenses = "SELECT SUM(b.montant) AS total_depenses 
                     FROM budget b 
                     JOIN type t ON b.id_type = t.id_type 
                     WHERE b.id_departement = :id_departement 
                     AND t.id_cate = 2 
                     AND b.date_budget <= :debut";
    $stmt = $conn->prepare($sql_depenses);
    $stmt->execute([
        ':id_departement' => $id_departement,
        ':debut' => $debut,
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_depenses = $result['total_depenses'] ?? 0;

    // Calcul du solde net (recettes - dépenses)
    $solde_debut = $total_recettes - $total_depenses;

    // Calcul des recettes (id_cate = 1 pour les recettes) jusqu'à la fin
    $sql_recettes = "SELECT SUM(b.montant) AS total_recettes 
                     FROM budget b 
                     JOIN type t ON b.id_type = t.id_type 
                     WHERE b.id_departement = :id_departement 
                     AND t.id_cate = 1 
                     AND b.date_budget <= :fin";
    $stmt = $conn->prepare($sql_recettes);
    $stmt->execute([
        ':id_departement' => $id_departement,
        ':fin' => $fin,
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_recettes2 = $result['total_recettes'] ?? 0;

    // Calcul des dépenses (id_cate = 2 pour les dépenses) jusqu'à la fin
    $sql_depenses = "SELECT SUM(b.montant) AS total_depenses 
                     FROM budget b 
                     JOIN type t ON b.id_type = t.id_type 
                     WHERE b.id_departement = :id_departement 
                     AND t.id_cate = 2 
                     AND b.date_budget <= :fin";
    $stmt = $conn->prepare($sql_depenses);
    $stmt->execute([
        ':id_departement' => $id_departement,
        ':fin' => $fin,
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_depenses2 = $result['total_depenses'] ?? 0;

    // Calcul du solde net (recettes - dépenses) à la fin
    $solde_fin = $total_recettes2 - $total_depenses2;

    return [
        'solde_debut' => $solde_debut,
        'solde_fin' => $solde_fin,
    ];
}


// Connexion à la base de





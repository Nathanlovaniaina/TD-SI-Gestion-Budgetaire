<?php
function insererNature($id_departement, $libelle) {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL
    $sql = "INSERT INTO Nature (nom, id_departement) VALUES (?, ?)";
    $stmt = mysqli_prepare($bdd, $sql);

    if (!$stmt) {
        die("Erreur de préparation : " . mysqli_error($bdd));
    }

    // Lier les paramètres : "s" = string, "i" = integer
    mysqli_stmt_bind_param($stmt, "si", $libelle, $id_departement);

    // Exécuter la requête
    if (mysqli_stmt_execute($stmt)) {
        echo "Insertion réussie dans Nature !";
    } else {
        echo "Erreur d'insertion dans Nature : " . mysqli_stmt_error($stmt);
    }

    // Fermer la requête et la connexion
    mysqli_stmt_close($stmt);
    mysqli_close($bdd);
}

function insererType($id_cate, $id_nature, $libelle) {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL avec les trois colonnes
    $sql = "INSERT INTO Type (id_cate, id_nature, libelle) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($bdd, $sql);

    if (!$stmt) {
        die("Erreur de préparation : " . mysqli_error($bdd));
    }

    // Lier les paramètres : "i" pour int, "s" pour string
    mysqli_stmt_bind_param($stmt, "iis", $id_cate, $id_nature, $libelle);

    // Exécuter la requête
    if (mysqli_stmt_execute($stmt)) {
        echo "Insertion réussie dans Type !";
    } else {
        echo "Erreur d'insertion dans Type : " . mysqli_stmt_error($stmt);
    }

    // Fermer la requête et la connexion
    mysqli_stmt_close($stmt);
    mysqli_close($bdd);
}

function getIdNature($nom) {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL
    $sql = "SELECT id_nature FROM Nature WHERE nom = ?";
    $stmt = mysqli_prepare($bdd, $sql);

    if (!$stmt) {
        die("Erreur de préparation : " . mysqli_error($bdd));
    }

    // Lier le paramètre : "s" = string
    mysqli_stmt_bind_param($stmt, "s", $nom);

    // Exécuter la requête
    mysqli_stmt_execute($stmt);

    // Récupérer le résultat
    $result = mysqli_stmt_get_result($stmt);

    // Vérifier si une ligne est trouvée
    if ($row = mysqli_fetch_assoc($result)) {
        $id = $row["id_nature"]; // Retourne uniquement l'ID
    } else {
        $id = null; // Retourne null si aucune nature trouvée
    }

    // Fermer la requête et la connexion
    mysqli_stmt_close($stmt);
    mysqli_close($bdd);
    return $id;
}

function getIdDepartement($nom) {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL
    $sql = "SELECT id_departement FROM Departement WHERE nom = ?";
    $stmt = mysqli_prepare($bdd, $sql);

    if (!$stmt) {
        die("Erreur de préparation : " . mysqli_error($bdd));
    }

    // Lier le paramètre : "s" = string
    mysqli_stmt_bind_param($stmt, "s", $nom);

    // Exécuter la requête
    mysqli_stmt_execute($stmt);

    // Récupérer le résultat
    $result = mysqli_stmt_get_result($stmt);

    // Vérifier si une ligne est trouvée
    if ($row = mysqli_fetch_assoc($result)) {
        $id = $row["id_departement"]; // Retourne uniquement l'ID
    } else {
        $id = null; // Retourne null si aucun département trouvé
    }

    // Fermer la requête et la connexion
    mysqli_stmt_close($stmt);
    mysqli_close($bdd);
    return $id;
}

function getIdCategorie($nom) {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL
    $sql = "SELECT id_cate FROM Categorie WHERE nom = ?";
    $stmt = mysqli_prepare($bdd, $sql);

    if (!$stmt) {
        die("Erreur de préparation : " . mysqli_error($bdd));
    }

    // Lier le paramètre : "s" = string
    mysqli_stmt_bind_param($stmt, "s", $nom);

    // Exécuter la requête
    mysqli_stmt_execute($stmt);

    // Récupérer le résultat
    $result = mysqli_stmt_get_result($stmt);

    // Vérifier si une ligne est trouvée
    if ($row = mysqli_fetch_assoc($result)) {
        $id = $row["id_cate"]; // Retourne uniquement l'ID
    } else {
        $id = null; // Retourne null si aucune catégorie trouvée
    }

    // Fermer la requête et la connexion
    mysqli_stmt_close($stmt);
    mysqli_close($bdd);
    return $id;
}

function getTousLesDepartements() {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL pour récupérer tous les départements
    $sql = "SELECT nom FROM Departement";
    $result = mysqli_query($bdd, $sql);

    // Vérifier si la requête s'est bien exécutée
    if (!$result) {
        die("Erreur de requête : " . mysqli_error($bdd));
    }

    // Créer un tableau pour stocker les noms
    $departements = array();

    // Récupérer les résultats ligne par ligne
    while ($row = mysqli_fetch_assoc($result)) {
        $departements[] = $row['nom']; // Ajouter le nom du département au tableau
    }

    // Fermer la connexion
    mysqli_close($bdd);

    return $departements; // Retourner le tableau des noms
}

function getTousLesNatures() {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL pour récupérer tous les noms des natures
    $sql = "SELECT nom FROM Nature";
    $result = mysqli_query($bdd, $sql);

    // Vérifier si la requête s'est bien exécutée
    if (!$result) {
        die("Erreur de requête : " . mysqli_error($bdd));
    }

    // Créer un tableau pour stocker les noms
    $natures = array();

    // Récupérer les résultats ligne par ligne
    while ($row = mysqli_fetch_assoc($result)) {
        $natures[] = $row['nom']; // Ajouter le nom de la nature au tableau
    }

    // Fermer la connexion
    mysqli_close($bdd);

    return $natures; // Retourner le tableau des noms
}
function getToutesLesCategories() {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL pour récupérer tous les noms des catégories
    $sql = "SELECT nom FROM Categorie";
    $result = mysqli_query($bdd, $sql);

    // Vérifier si la requête s'est bien exécutée
    if (!$result) {
        die("Erreur de requête : " . mysqli_error($bdd));
    }

    // Créer un tableau pour stocker les noms
    $categories = array();

    // Récupérer les résultats ligne par ligne
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['nom']; // Ajouter le nom de la catégorie au tableau
    }

    // Fermer la connexion
    mysqli_close($bdd);

    return $categories; // Retourner le tableau des noms
}
function getToutesLesRequetesBudgetaires() {
    include("connexion2.php"); // Inclure la connexion

    // Préparer la requête SQL pour récupérer toutes les requêtes budgétaires
    $sql = "SELECT * FROM RequeteBudgetaire";
    $result = mysqli_query($bdd, $sql);

    // Vérifier si la requête s'est bien exécutée
    if (!$result) {
        die("Erreur de requête : " . mysqli_error($bdd));
    }

    // Créer un tableau pour stocker les résultats
    $requetes = array();

    // Récupérer les résultats ligne par ligne
    while ($row = mysqli_fetch_assoc($result)) {
        $requetes[] = $row; // Ajouter toute la ligne au tableau
    }

    // Fermer la connexion
    mysqli_close($bdd);

    return $requetes; // Retourner le tableau des requêtes
}


function supprimerRequeteBudgetaire($id_requete) {
    include("connexion2.php"); // Connexion à la base ($bdd)

    // Sécuriser l'ID
    $id_requete = intval($id_requete);

    $sql = "DELETE FROM RequeteBudgetaire WHERE id_requete = $id_requete";
    $result = mysqli_query($bdd, $sql);

    if (!$result) {
        die("Erreur lors de la suppression : " . mysqli_error($bdd));
    }

    mysqli_close($bdd);
}

function validerRequeteBudgetaire($id_requete, $id_departement, $id_type, $date_budget, $description) {
    include("connexion2.php");

    // Nettoyage des entrées (comme avant)
    $id_requete = intval($id_requete);
    $id_departement = intval($id_departement);
    $id_type = intval($id_type);
    $description = mysqli_real_escape_string($bdd, $description);
    $date_budget = mysqli_real_escape_string($bdd, $date_budget);

    // 1. Récupérer la valeur de la requête (inchangé)
    $sql_select = "SELECT valeur FROM RequeteBudgetaire WHERE id_requete = $id_requete";
    $result = mysqli_query($bdd, $sql_select);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("Requête introuvable ou erreur lors de la récupération : " . mysqli_error($bdd));
    }

    $row = mysqli_fetch_assoc($result);
    $valeur = $row['valeur'];

    // 2. MODIFICATION : Mettre à jour le budget existant au lieu d'insérer
    $sql_update = "UPDATE Budget 
                   SET montant = montant + $valeur  /* Ajoute la valeur à l'ancien montant */
                   WHERE 
                       id_departement = $id_departement 
                       AND id_type = $id_type ";

    if (!mysqli_query($bdd, $sql_update)) {
        die("Erreur lors de la mise à jour du budget : " . mysqli_error($bdd));
    }

    // 3. Supprimer la requête (inchangé)
    $sql_delete = "DELETE FROM RequeteBudgetaire WHERE id_requete = $id_requete";
    mysqli_query($bdd, $sql_delete);

    mysqli_close($bdd);
}

?>





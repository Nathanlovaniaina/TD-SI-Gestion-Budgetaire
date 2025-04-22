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
?>





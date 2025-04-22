<?php
    include("connexion.php");

    function getDepartementById($id) {
    // Connexion à la base de données
        $conn = dbConnect();

    // Sécurisation de l'ID
        $id = mysqli_real_escape_string($conn, $id);

    // Requête SQL
        $sql = "SELECT * FROM Departement WHERE id_departement = $id";
        $result = mysqli_query($conn, $sql);

    // Vérification du résultat
        if ($result && mysqli_num_rows($result) > 0) {
            $departement = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        } else {
            $departement = null;
        }
        mysqli_close($conn);

        return $departement;
    }

function countAllOptimized() {
    // Connexion à la base de données
    $conn = dbConnect();

    // Requête SQL optimisée
    $sql = "SELECT COUNT(*) AS total FROM Departement";
    $result = mysqli_query($conn, $sql);

    // Récupération du nombre total
    $row = mysqli_fetch_assoc($result);
    $count = $row['total'];

    // Fermeture de la connexion
    mysqli_close($conn);

    return $count;
}

function getCategorieById($id) {
    // Connexion à la base de données
        $conn = dbConnect();

    // Sécurisation de l'ID
        $id = mysqli_real_escape_string($conn, $id);

    // Requête SQL
        $sql = "SELECT * FROM Categorie WHERE id_cate = $id";
        $result = mysqli_query($conn, $sql);

    // Vérification du résultat
        if ($result && mysqli_num_rows($result) > 0) {
            $categorie = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        } else {
            $categorie = null;
        }
        mysqli_close($conn);

        return $categorie;
    }

    function countCate() {
        // Connexion à la base de données
        $conn = dbConnect();
    
        // Requête SQL optimisée
        $sql = "SELECT COUNT(*) AS total FROM Categorie";
        $result = mysqli_query($conn, $sql);
    
        // Récupération du nombre total
        $row = mysqli_fetch_assoc($result);
        $count = $row['total'];
    
        // Fermeture de la connexion
        mysqli_close($conn);
    
        return $count;
    }

function checkLogin($identifiant, $mdp) {
    // Connexion à la base de données
    $conn = dbConnect();

    // Requête préparée pour éviter les injections SQL
    $sql = "SELECT * FROM User WHERE identifiant = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $identifiant);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Vérification du résultat
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Vérification du mot de passe (si stocké en hash)
        if ($mdp == $user['mdp']) {
            return $user; // Connexion réussie, retourne les infos de l'utilisateur
        }
    }

    // Si échec, retourner false
    return false;
}

?>


<?php
session_start();
//include 'dbConnect.php'; // Assurez-vous d'inclure votre fichier de connexion
include '../../inc/functions.php'; // Fichier où se trouve checkLogin()

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $identifiant = $_GET['identifiant'];
    $mdp = $_GET['mdp'];

    echo $identifiant;
    echo $mdp;

    $user = checkLogin($identifiant, $mdp);

    if ($user) {
        $_SESSION['user_id'] = $user['id_departement'];
        $_SESSION['identifiant'] = $user['identifiant'];

        echo "Connexion réussie. Bienvenue " . htmlspecialchars($user['identifiant']) . "!";
        header("Location: ../home.php");
    } else {
        echo "Identifiant ou mot de passe incorrect.";
    }
}
?>
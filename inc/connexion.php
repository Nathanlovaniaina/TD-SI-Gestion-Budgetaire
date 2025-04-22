<?php
function dbConnect() {
    $host = 'localhost';  // Remplace par l'hôte de ta base de données
    $dbname = 'gestion_budget';  // Remplace par le nom de ta base de données
    $username = 'root';  // Remplace par ton nom d'utilisateur
    $password = '';  // Remplace par ton mot de passe (laisser vide pour XAMPP)

    // Connexion à MySQL
    $conn = mysqli_connect($host, $username, $password, $dbname);

    // Vérification de la connexion
    if (!$conn) {
        die("Erreur de connexion : " . mysqli_connect_error());
    }

    return $conn;
}

?>

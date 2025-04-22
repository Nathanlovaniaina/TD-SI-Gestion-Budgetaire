<?php
function connectDB() {
    $host = "localhost";  // Adresse du serveur MySQL (ex: 127.0.0.1)
    $dbname = "gestion_budget";  // Nom de la base de données
    $username = "root";  // Nom d'utilisateur MySQL
    $password = "";  // Mot de passe MySQL (laisser vide si pas de mot de passe)

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active le mode exception
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage()); // Affiche l'erreur et arrête le script
    }
}


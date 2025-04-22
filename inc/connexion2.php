<?php
$host = "localhost";  // Serveur MySQL (généralement "localhost")
$dbname = "gestion_budget";  // Remplace par le nom de ta base
$username = "root";  // Nom d'utilisateur (par défaut "root" sous XAMPP)
$password = "";  // Mot de passe (par défaut vide sous XAMPP)


$bdd = mysqli_connect($host, $username, $password, $dbname);
if($bdd){
    // echo 'connexion reussie';
}
else{
    // echo 'erreur de connexion';
}
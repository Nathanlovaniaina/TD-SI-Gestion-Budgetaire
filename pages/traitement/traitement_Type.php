<?php
include("../../inc/fonction.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nature']) && isset($_POST['type']) && isset($_POST['categorie']))  {
    $libelle = trim($_POST['type']);
    $id_nature = intval($_POST['nature']);
    $id_cat = intval($_POST['categorie']);

    insererType($id_cat, $id_nature, $libelle);

    echo "<script>alert('Nature ajoutée avec succès !'); window.location.href='../Type.php';</script>";
    exit();
} else {
    echo "Erreur : Données invalides.";
}
?>

<?php
include("../../inc/fonction.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nature']) && isset($_POST['departement'])) {
    $libelle = trim($_POST['nature']);
    $id_departement = intval($_POST['departement']);

    insererNature($id_departement, $libelle);

    echo "<script>alert('Nature ajoutée avec succès !'); window.location.href='../Nature.php';</script>";
    exit();
} else {
    echo "Erreur : Données invalides.";
}
?>

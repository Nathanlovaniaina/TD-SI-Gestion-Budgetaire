<?php
include("../../inc/fonction.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_requete = $_POST['id_requete'];
    $id_departement = $_POST['id_departement'];
    $id_type = $_POST['id_type'];
    $date_budget = $_POST['date_budget'];
    $description = $_POST['description'];

    validerRequeteBudgetaire($id_requete, $id_departement, $id_type, $date_budget, $description);
}

header("Location: ../liste_requetteBudgetaire.php");
exit();

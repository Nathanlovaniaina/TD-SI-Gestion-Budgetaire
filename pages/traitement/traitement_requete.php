<?php
include("../../inc/fonction.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_requete'])) {
    $id = $_POST['id_requete'];
    supprimerRequeteBudgetaire($id);
}

// Redirection vers la liste après suppression
header("Location: ../liste_requetteBudgetaire.php");
exit();

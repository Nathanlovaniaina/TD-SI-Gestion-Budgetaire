<?php
session_start();
include("../../function/exportCSV.php");

$date_debut = $_GET['date_debut'];
$date_fin = $_GET['date_fin'];
$id_departement_possession = $_GET['id_depart'];

// exportBudgetDetailsCSV($date_debut, $date_fin, $id_departement_possession);

$data = get_bilan_budgetaire_($date_debut, $date_fin, $id_departement_possession);

print_r($data);

// Exporter en CSV
exportBudgetToCSV($date_debut, $date_fin, $id_departement_possession);

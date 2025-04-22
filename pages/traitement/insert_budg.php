<?php
include("../../function/budget.php");
$id_departement = $_POST['id_departement'];
$id_type = $_POST['type'];
$montant = $_POST['montant'];
$date = $_POST['daty'];
$desc = $_POST['desc'];
insertBudget($montant,$date, $id_departement, $id_type, $desc);

header("Location: ../home.php");
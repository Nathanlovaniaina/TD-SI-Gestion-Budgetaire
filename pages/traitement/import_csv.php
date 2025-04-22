<?php
include("../../function/budget.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_FILES["csv_file"]) || $_FILES["csv_file"]["error"] != 0) {
        die("Erreur : Problème avec le fichier.");
    }

    $filePath = $_FILES["csv_file"]["tmp_name"];

    if (!file_exists($filePath) || !is_readable($filePath)) {
        die("Erreur : Fichier introuvable ou illisible.");
    }

    $handle = fopen($filePath, "r");
    fgetcsv($handle); // Ignorer la ligne des en-têtes
    $insertedRows = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if (count($data) < 4) continue;

        list($montant, $date_budget, $id_type, $description) = $data;
        // print_r($data);
        // echo "Description récupérée : " . htmlspecialchars($data[4]) . "<br>";

        
        if (insertBudget($data[0], $data[1], $data[2], $data[3], $data[4])) {
            $insertedRows++;
        }
    }

    fclose($handle);
    echo "Importation réussie : $insertedRows budgets insérés.";
}
header("Location: ../home.php");
?>

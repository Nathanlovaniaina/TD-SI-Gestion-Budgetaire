<?php

include("budget.php");

function exportBudgetToCSV($date_debut, $date_fin, $id_departement) {
    $bilan = get_bilan_budgetaire_($date_debut, $date_fin, $id_departement, 1);

    // Check if data is valid
    if (empty($bilan)) {
        die("Aucune donnée trouvée pour les dates et département spécifiés.");
    }

    $filename = "budget_export_" . date("Y-m-d") . ".csv";

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Add UTF-8 BOM for Excel compatibility
    echo "\xEF\xBB\xBF";

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write CSV header
    fputcsv($output, ["Catégorie", "Type", "Nature", "Prévision (€)", "Réalisation (€)", "Écart (€)"]);

    // Write recettes (revenues) data
    foreach ($bilan['recettes'] as $budget) {
        fputcsv($output, [
            "Recettes",
            $budget['type'], // Corrected key
            $budget['nature'],
            $budget['prevision'],
            $budget['realisation'], // Corrected key
            $budget['ecart']
        ]);
    }

    // Write total recettes
    fputcsv($output, [
        "Total Recettes",
        "",
        "",
        $bilan['totaux']['total_recettes']['prevision'], // Corrected path
        $bilan['totaux']['total_recettes']['realisation'], // Corrected path
        $bilan['totaux']['total_recettes']['ecart'] // Corrected path
    ]);

    // Write depenses (expenses) data
    foreach ($bilan['depenses'] as $budget) {
        fputcsv($output, [
            "Dépenses",
            $budget['type'], // Corrected key
            $budget['nature'],
            $budget['prevision'],
            $budget['realisation'], // Corrected key
            $budget['ecart']
        ]);
    }

    // Write total depenses
    fputcsv($output, [
        "Total Dépenses",
        "",
        "",
        $bilan['totaux']['total_depenses']['prevision'], // Corrected path
        $bilan['totaux']['total_depenses']['realisation'], // Corrected path
        $bilan['totaux']['total_depenses']['ecart'] // Corrected path
    ]);

    // Write resultat
    fputcsv($output, [
        "Résultat",
        "",
        "",
        $bilan['totaux']['resultat']['prevision'], // Corrected path
        $bilan['totaux']['resultat']['realisation'], // Corrected path
        $bilan['totaux']['resultat']['ecart'] // Corrected path
    ]);

    // Close output stream
    fclose($output);
    exit();
}

function exportArrayToCSV($data, $filename = "export.csv") {
    if (empty($data)) {
        die("Aucune donnée à exporter.");
    }

    // Définir les en-têtes pour le téléchargement du fichier CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Ouvrir un flux de sortie pour écrire le CSV
    $output = fopen('php://output', 'w');

    // Écrire l'en-tête du CSV (les clés du premier élément du tableau)
    fputcsv($output, array_keys($data[0]));

    // Écrire chaque ligne du tableau
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}


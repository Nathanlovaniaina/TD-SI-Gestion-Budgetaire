<?php
session_start();
require('../../function/fpdf186/fpdf.php');
include("../../function/budget.php");

$date_debut = $_GET['date_debut'];
$date_fin = $_GET['date_fin'];
$id_departement_possession = $_GET['id_depart'];
if($id_departement_possession == 0){
    $id_departement_possession = $_SESSION['user_id'];
}
$departement = getDepartementById($id_departement_possession);
$id_departement_possession = $_GET['id_depart'];
$budgetRecette = getBudgetDetails($date_debut, $date_fin, $id_departement_possession, 1);
$budgetDepense = getBudgetDetails($date_debut, $date_fin, $id_departement_possession, 2);

class PDF extends FPDF
{
    private $departement;

    function setDepartement($departement)
    {
        $this->departement = $departement;
    }

    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->setTextColor(0, 0, 255);
        $this->Cell(190, 10, 'TABLEAU BUDGETAIRE', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Fait a Antananarivo, le ' . date('d/m/Y'), 0, 1, 'L');
        $this->Cell(0, 10, 'Direction ' . $this->departement['nom'], 0, 0, 'L');
    }

    function TableHeader()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(50, 10, 'Categorie', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Type', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Nature', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Prevision ', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Realisation ', 1, 1, 'C', true);
    }

    function TableRow($categorie, $type, $nature, $prevision, $realisation)
    {
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 10, utf8_decode($categorie), 1);
        $this->Cell(40, 10, utf8_decode($type), 1);
        $this->Cell(40, 10, utf8_decode($nature), 1);
        $this->Cell(30, 10, number_format($prevision, 2), 1, 0, 'R');
        $this->Cell(30, 10, number_format($realisation, 2), 1, 1, 'R');
    }

    function TableFooter($total_prevision, $total_realisation)
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(130, 10, 'TOTAL', 1, 0, 'C', true);
        $this->Cell(30, 10, number_format($total_prevision, 2), 1, 0, 'R', true);
        $this->Cell(30, 10, number_format($total_realisation, 2), 1, 1, 'R', true);
    }
}

// Création du PDF
$pdf = new PDF();
$pdf->setDepartement($departement);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Affichage du département et de la période
$pdf->Cell(100, 10, "Departement: " . $departement['nom'], 0, 1);
$pdf->Cell(100, 10, "Periode: $date_debut a $date_fin", 0, 1);
$pdf->Ln(5);

// Recettes
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'RECETTES', 1, 1, 'C');
$pdf->TableHeader();

$total_prevision_recette = 0;
$total_realisation_recette = 0;

foreach ($budgetRecette as $budget) {
    $prevision = calculPrevision($budget['id_type'], $date_debut);
    $realisation = $budget['montant'];

    $total_prevision_recette += $prevision;
    $total_realisation_recette += $realisation;

    $pdf->TableRow('', $budget['type_depense'], $budget['nature'], $prevision, $realisation);
}
$pdf->TableFooter($total_prevision_recette, $total_realisation_recette);
$pdf->Ln(5);

// Dépenses
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'DEPENSES', 1, 1, 'C');
$pdf->TableHeader();

$total_prevision_depense = 0;
$total_realisation_depense = 0;

foreach ($budgetDepense as $budget) {
    $prevision = calculPrevision($budget['id_type'], $date_debut);
    $realisation = $budget['montant'];

    $total_prevision_depense += $prevision;
    $total_realisation_depense += $realisation;

    $pdf->TableRow('', $budget['type_depense'], $budget['nature'], $prevision, $realisation);
}
$pdf->TableFooter($total_prevision_depense, $total_realisation_depense);
$pdf->Ln(5);

// Résultat global
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'RESULTAT GLOBAL', 1, 1, 'C');

$resultat_prevision = $total_prevision_recette - $total_prevision_depense;
$resultat_realisation = $total_realisation_recette - $total_realisation_depense;

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(130, 10, "Resultat Previonnel", 1, 0, 'C');
$pdf->Cell(60, 10, number_format($resultat_prevision, 2), 1, 1, 'R');
$pdf->Cell(130, 10, "Resultat Realise", 1, 0, 'C');
$pdf->Cell(60, 10, number_format($resultat_realisation, 2), 1, 1, 'R');

// Génération du fichier PDF
$pdf->Output();
?>
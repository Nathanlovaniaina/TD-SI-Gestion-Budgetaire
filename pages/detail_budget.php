<?php
    session_start();
    include("../function/budget.php");

    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $id_departement_proprietaire = $_SESSION['user_id'];
    $id_departement_possession = $_POST['departement'];
    $budgetRecette=getBudgetDetails($date_debut, $date_fin,$id_departement_possession,1);
    $budgetDepense=getBudgetDetails($date_debut, $date_fin,$id_departement_possession,2);
    $is_autorize = estProprietaire($id_departement_proprietaire, $id_departement_possession);
    if(!$is_autorize && $id_departement_possession != $id_departement_proprietaire && $id_departement_possession != 0){
        header("Location: home.php");
    }
    $soldes = calculerSolde($id_departement_possession, $date_debut, $date_fin);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau Budget</title>
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/detail_budget.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h3>Gestion Budget</h3>
        <ul class="list">
            <li><a href="home.php">Accueil</a></li>
            <li><a href="Nature.php">Insertions</a></li>
            <li><a href="traitement/logout.php">Quitter</a></li>
        </ul>
    </div>
</div>

<div class="container mt-4">
    <div class="table-responsive">
        <table class="table table-bordered table-budget">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Type</th>
                    <th>Nature</th>
                    <th>Prévision (€)</th>
                    <th>Réalisation (€)</th>
                    <th>Écart (€)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold">
                    <td colspan="3">Solde de depart</td>
                    <td colspan="3" ><?php echo $soldes['solde_debut'] ?> </td>
                </tr>
                <tr>
                    <td rowspan="<?php count($budgetRecette) +1 ?>">Recettes</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php foreach ($budgetRecette as $budget) :?>
                    <tr>
                        <td></td>
                        <td><?php echo $budget['type_depense'] ?></td>
                        <td><?php echo $budget['nature'] ?></td>
                        <td><?php echo calculPrevision($budget['id_type'],$date_debut) ?></td>
                        <td><?php echo $budget['montant'] ?></td>
                        <td><?php echo calculecart(calculPrevision($budget['id_type'],$date_debut),$budget['montant']) ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <tr class="fw-bold">
                    <?php $total_recett = calcul_total($budgetRecette,$date_debut,$date_fin); ?>
                    <td colspan="2">Total Recettes</td>
                    <td></td>
                    <td><?php echo $total_recett[1] ?></td>
                    <td><?php echo $total_recett[0] ?></td>
                    <td><?php echo $total_recett[2] ?></td>
                </tr>
                <tr>
                    <td rowspan="<?php count($budgetDepense) +1 ?>">Dépenses</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php foreach ($budgetDepense as $budget) :?>
                    <tr>
                        <td></td>
                        <td><?php echo $budget['type_depense'] ?></td>
                        <td><?php echo $budget['nature'] ?></td>
                        <td><?php echo calculPrevision($budget['id_type'],$date_debut) ?></td>
                        <td><?php echo $budget['montant'] ?></td>
                        <td><?php echo calculecart(calculPrevision($budget['id_type'],$date_debut),$budget['montant']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="fw-bold">
                <?php $total_depense = calcul_total($budgetDepense,$date_debut,$date_fin); ?>
                    <td colspan="2">Total Depenses</td>
                    <td></td>
                    <td><?php echo $total_depense[1] ?></td>
                    <td><?php echo $total_depense[0] ?></td>
                    <td><?php echo $total_depense[2] ?></td>
                </tr>
                <tr class="fw-bold">
                <?php $resultat_prevision = $total_recett[1]-$total_depense[1];
                        $resultat_realiser = $total_recett[0]-$total_depense[0];
                        $resultat_ecart = $total_recett[2]-$total_depense[2];
                ?>
                    <td colspan="2">Résultat</td>
                    <td></td>
                    <td><?php echo $resultat_prevision ?></td>
                    <td><?php echo $resultat_realiser ?></td>
                    <td><?php echo $resultat_ecart?></td>
                </tr>
                <tr class="fw-bold">
                    <td colspan="3">Solde de fin</td>
                    <td colspan="3" ><?php echo $soldes['solde_fin'] ?></td>
                </tr>
            </tbody>
        </table>
        <div class="btn"><a href="traitement/telechargerPDF.php?date_debut=<?php echo $date_debut ?>&date_fin=<?php echo $date_fin ?>&id_depart=<?php echo $id_departement_possession ?>">Telecharger</a></div>
        <div class="btn"><a href="traitement/traitementExportbugetCSV.php?date_debut=<?php echo $date_debut ?>&date_fin=<?php echo $date_fin ?>&id_depart=<?php echo $id_departement_possession ?>">Importer en format CSV</a></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

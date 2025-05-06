<?php 
    include ("../inc/fonction.php");
    $requetes = getToutesLesRequetesBudgetaires();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des requêtes budgétaires</title>
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/detail_budget.css">
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<body>
<div class="container">
    <div class="header">
        <h3>Gestion Budget</h3>
        <ul class="list">
            <li><a href="home.php">Accueil</a></li>
            <li><a href="Nature.php">Insertions</a></li>
            <li><a href="liste_requetteBudgetaire.php">Demande</a></li>
            <li><a href="traitement/logout.php">Quitter</a></li>
        </ul>
    </div>
</div>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Liste des requêtes budgétaires</h2>

    <?php if (count($requetes) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID Requête</th>
                    <th>Valeur (€)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requetes as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['id_requete']); ?></td>
                        <td><?php echo number_format($r['valeur'], 2, ',', ' '); ?></td>
                        <td>
                            <form action="traitement/validationRequette.php" method="post" class="d-inline">
                                <input type="hidden" name="id_requete" value="<?php echo $r['id_requete']; ?>">
                                <input type="hidden" name="id_departement" value="3">
                                <input type="hidden" name="id_type" value="1"> 
                                <input type="hidden" name="date_budget" value="<?php echo date('Y-m-d'); ?>">
                                <input type="hidden" name="description" value="Budget validé via requête #<?php echo $r['id_requete']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Valider</button>
                            </form>
                            <form action="traitement/traitement_requete.php" method="post" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                <input type="hidden" name="id_requete" value="<?php echo $r['id_requete']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" href="./traitement/traitement_requete.php">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Aucune requête budgétaire trouvée.</div>
    <?php endif; ?>
</div>

<script src="../assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

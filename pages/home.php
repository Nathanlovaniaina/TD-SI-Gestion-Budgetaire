<?php 
    session_start();
    include ("../inc/functions.php");
    $id_dep = countAllOptimized();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
            <?php if($_SESSION['user_id'] == 1) {?>
                <li><a href="liste_requetteBudgetaire.php">Demande</a></li>
            <?php }?>
            <li><a href="traitement/logout.php">Quitter</a></li>
        </ul>
    </div>
</div>
    <div class="container mt-4">
    <?php if($_SESSION['user_id'] == 1) {?>
        <form action="accueil.php" method="get">
            <h3>Insertion de budget</h3>
            <div>
                Departement :
                <select name="sous_departement" id="">
                    <option value=""></option>
                    <?php for ($i=1; $i <= $id_dep ; $i++) { ?>
                        <option value="<?php echo getDepartementById($i)['id_departement'] ?>"><?php echo getDepartementById($i)['nom'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div><input type="submit" value="Valider"></div>
        </form>
        <form action="traitement/import_csv.php" method="post" enctype="multipart/form-data">
            <h3>Insertion de budget CSV</h3>

            <!-- Sélection du fichier CSV -->
            <div>
                <label for="csv_file">Fichier CSV :</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            </div>

            <!-- Bouton de validation -->
            <div>
                <input type="submit" value="Importer">
            </div>
        </form>
    <?php }?>
    <form action="detail_budget.php" method="post">
        <h3>Detail budget</h3>
        <label for="">Departement</label>
        <select name="departement" id="">
                <option value="0">Tous</option>
                <?php for ($i=1; $i <= $id_dep ; $i++) { ?>
                    <option value="<?php echo getDepartementById($i)['id_departement'] ?>"><?php echo getDepartementById($i)['nom'] ?></option>
                <?php } ?>
        </select>
        <label for="">Date debut</label>
        <input type="date" name="date_debut" id="">
        <label for="">Date fin</label>
        <input type="date" name="date_fin" id="">
        <div><input type="submit" value="Valider"></div>
    </form>
    </div>
</body>
</html>
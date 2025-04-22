<?php
include("../inc/fonction.php");
$departement = getTousLesDepartements();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création Nature</title>
    <link rel="stylesheet" href="../assets/css/style1.css">
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
    <nav>
        <a href="Nature.php">Créer une Nature</a>
        <a href="Type.php">Créer un Type</a>
    </nav>

    <div class="container">
        <h2>Créer une Nature</h2>
        <form action="traitement/traitement_Nature.php" method="post"> 
            <div class="form-group">
                <label for="nature">Nom :</label>
                <input type="text" id="nature" name="nature" required>
            </div>
            <div class="form-group">
                <label for="departement">Département :</label>
                <select id="departement" name="departement" required>
                    <?php foreach ($departement as $nom) { 
                        $id = getIdDepartement($nom); ?>
                        <option value="<?= $id ?>"><?= htmlspecialchars($nom) ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit">Valider</button>
        </form>
    </div>

    <footer>
        <p>Gestion de Budget - © 2025</p>
    </footer>
    </div>
</body>
</html>

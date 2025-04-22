<?php 
    include ("../function/budget.php");
    $id_departement = $_GET['sous_departement'];
    $types = getTypesByDepartement($id_departement);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/detail_budget.css">
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/detail_budget.css">
    <link rel="stylesheet" href="../assets/css/home.css">
    <title>Ajout budget</title>
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
    <form action="traitement/insert_budg.php" method="post">
        <input type="hidden" name="id_departement" value="<?php echo $id_departement; ?>">
        <div>
            Type :
            <select name="type" id="">
                <?php foreach ($types as $t) { ?>
                    <option value="<?php echo $t['id_type'] ?>"><?php echo $t['type_depense'] ?> <?php if($t['id_cate'] == 1){?> (Recette)<?php }  ?> <?php if($t['id_cate'] == 2){?> (Depense)<?php }  ?></option>
                <?php } ?>
            </select>
        </div>
        <div>Date : <input type="date" name="daty" id=""></div>
        <div>Montant : <input type="number" name="montant" id=""></div>
        <div>Description: <input type="text" name="desc" id=""></div>
        <div><input type="submit" value="Valider"></div>
    </form>
    </div>
</body>
</html>
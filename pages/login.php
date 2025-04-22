<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="../assets/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="container mt-4">
    <h2>Connexion</h2>
    <form action= "traitement/traitement.php" method="GET">
        <label for="identifiant">Identifiant :</label>
        <input type="text" name="identifiant" value="admin_finance" required>
        <br>
        <label for="mdp">Mot de passe :</label>
        <input type="password" name="mdp" value="hashedpassword1" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>
    </div>
</body>
</html>

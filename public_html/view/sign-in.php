<?php
require "../controller/sign.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>

<body>
    <?php if(!isset($_SESSION['user']) || !$_SESSION['user']['is_authenticated']) : ?> 
    <div class="form-sign">
        <h1> Connexion : </h1>
        <form method="POST">
            <label for="pseudo">Nom d'utilisateur</label>
            <input type="text" name="username">
            <label for="pseudo">Mot de passe</label>
            <input type="text" name="password">
            <input type="submit" value="sign-in" name="sign-in">
        </form>
        <a href="sign-up.php"> S'inscrire</a>
    </div>
    
    <?php else : ?>
        <h1> Connexion : </h1>
        <h2> Vous êtes déjà connecter : </h2>
        <a href="../controller/logout.php"> Deconnexion</a>

    <?php endif; ?>
</body>

</html>
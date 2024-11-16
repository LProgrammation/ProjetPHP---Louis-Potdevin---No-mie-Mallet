<?php
require "../controller/sign.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../styles/signupin.css">
</head>

<body>
    <div class="form-sign">
    <?php if(!isset($_SESSION['user']) || !$_SESSION['user']['is_authenticated']) : ?> 
        <h1> Connexion : </h1>
        <form method="POST">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username">
            <label for="password">Mot de passe</label>
            <input type="password" name="password">
            <input type="submit" value="sign-in" name="sign-in">
        </form>
        <a href="sign-up.php"> S'inscrire</a>
    
    
    <?php else : ?>
        <h1> Connexion : </h1>
        <h3> Vous êtes déjà connecter !</h3>
        <a href="../controller/logout.php"> Deconnexion</a>
        <a href="votes-ideas.php"> votée pour une idée</a>
        <a href="ideas.php"> Soumettre une idée</a>
    

    <?php endif; ?>
    </div>
</body>

</html>
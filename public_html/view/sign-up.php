<?php
require "../controller/sign.php" ;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../styles/signupin.css">

</head>

<body>
    <div class="form-sign">
        <?php if(isset($registerRes) && $registerRes == "duplicate") :  ?>
            <h1>Cette utilisateur existe déjà ! </h1>
            <?php elseif(isset($registerRes) && $registerRes != "success") : ?>
            <h1><?php echo $registerRes?> </h1>
            <?php elseif(isset($registerRes) && $registerRes == "success") : ?>
            <h1>Utilisateur enregistrer avec succès ! </h1>
        <?php endif ; ?>
        <h1> Inscription : </h1>
        <form method="POST">
            <label for="pseudo">Nom d'utilisateur</label>
            <input type="text" name="username">
            <label for="pseudo">Mot de passe</label>
            <input type="password" name="password">
            <input type="submit" value="sign-up" name="sign-up">
        </form>
        <a href="sign-in.php"> Se connecter</a>
    </div>
</body>

</html>
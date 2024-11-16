<?php
require "../controller/ideas.php"

?>
<!DOCTYPE html>
<html>

<head>
    <title>Boite à idées</title>
    <link rel="stylesheet" href="../styles/ideas.css">
</head>

<body>
    <h1>Boite à idées</h1>
    <p>Une idée à nous soumettre ? <br>
        Veuillez remplir le formulaire ci-dessous.</p>
        <form method="post">
        <label for="titre">Titre Idée :</label>
        <input type="text" name="titre" required></input><br>
        <label for="description">Description :</label>
        <textarea name="description" required></textarea><br>

        <button type="submit">Soumettre l'idée</button>
    </form>
    <a href="sign-in.php"> Page de connexion</a>
    <a href="votes-ideas.php"> Votée pour une idée</a>
</body>

</html>
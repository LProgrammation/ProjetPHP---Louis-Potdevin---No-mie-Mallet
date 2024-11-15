<?php
require "../controller/ideas.php"

?>
<!DOCTYPE html>
<html>

<head>
    <title>Boite à idées</title>
</head>

<body>
    <h1>Boite à idées</h1>
    <p>Une idée à nous soumettre ? <br>
        Veuillez remplir le formulaire ci-dessous.</p>
    <form method="post">
        <label for="description">Idée :</label>
        <textarea name="description" required></textarea><br>

        <button type="submit">Soumettre l'idée</button>
    </form>
</body>

</html>
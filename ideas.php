<?php
session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_authenticated']) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user']['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ideas = $_POST['ideas'];
    $votes = $_POST['votes'];
    $author = $userName;

    $ideasFile = 'ideas.json';
    $ideas = json_decode(file_get_contents($ideasFile), true) ?? [];
    $newIdea = [
        'title' => $title,
        'description' => $description,
        'author' => $author,
        'date' => $date,
        'votes' => ['positive' => 0, 'negative' => 0],
    ];
    $ideas[] = $newIdea;
    file_put_contents($ideasFile, json_encode($ideas, JSON_PRETTY_PRINT));

    header("Location: votes-ideas.php");
    exit();
}
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
        <label for="title">Idée :</label>
        <input type="text" name="title" required><br>

        <label for="description">Description :</label>
        <textarea name="description" required></textarea><br>

        <button type="submit">Soumettre l'idée</button>
    </form>
</body>

</html>
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

<?php
session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_authenticated']) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user']['username'];

// Boucle pour sécuriser les entrées + historique des idées + création d'idées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['description']) && !empty($_POST['description'])) {
        $description = trim($_POST['description']);

        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    } else {
        header("Location: votes-ideas.php?error=empty_description");
        exit();
    }

    $author = $userName;
    $date = date('Y-m-d H:i:s');
    $ideasFile = 'ideas.json';
    $ideas = file_exists($ideasFile) ? json_decode(file_get_contents($ideasFile), true) : [];


    $newIdea = [
        'description' => $description,
        'author' => $author,
        'date' => $date,
        'votes' => ['positive' => 0, 'negative' => 0]
    ];
    $ideas[] = $newIdea;

    // Stockage dans un JSON pour avoir le contrôle des données notamment + affichage en cas d'erreurs
    try {
        file_put_contents($ideasFile, json_encode($ideas, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    } catch (JsonException $e) {
        header("Location: votes-ideas.php?error=json_encoding");
        exit();
    }
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
        <label for="description">Idée :</label>
        <textarea name="description" required></textarea><br>

        <button type="submit">Soumettre l'idée</button>
    </form>
</body>

</html>
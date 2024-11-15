<?php

// USER : vérifie si le compte est connecté, au cas contraire, retourne à la page login.php

session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_authenticated']) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user']['username'];

$ideasFile = 'ideas.json';
$votesFile = 'votes.json';
$ideas = [];
$votes = [];

try {
    if (file_exists($ideasFile)) {
        $ideas = json_decode(file_get_contents($ideasFile), true) ?? [];
    }
    if (file_exists($votesFile)) {
        $votes = json_decode(file_get_contents($votesFile), true) ?? [];
    }
} catch (JsonException $e) {
    header("Location: display_ideas.php?error=json_read_error");
    exit();
}

// Traitement et validation des votes

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validation de l'index de l'idée (doit être un nombre valide)
    if (isset($_POST['ideaIndex']) && is_numeric($_POST['ideaIndex'])) {
        $ideaIndex = (int) $_POST['ideaIndex'];
        if (!isset($ideas[$ideaIndex])) {
            // Si l'index de l'idée est invalide, redirection avec message d'erreur
            header("Location: display_ideas.php?error=invalid_idea_index");
            exit();
        }
    } else {
        // Si l'index de l'idée est manquant ou invalide, redirection
        header("Location: display_ideas.php?error=missing_idea_index");
        exit();
    }


    if (isset($_POST['vote']) && in_array($_POST['vote'], ['positive', 'negative'])) {
        $voteType = $_POST['vote'];
    } else {
        header("Location: display_ideas.php?error=invalid_vote_type");
        exit();
    }

    $idea = &$ideas[$ideaIndex];
    $ideaId = $idea['title'] . '-' . $idea['author'];

    // Vérification et enregistrement voté

    if (isset($votes[$ideaId][$userName])) {
        // L'utilisateur a déjà voté, redirection avec un message d'erreur
        header("Location: display_ideas.php?error=already_voted");
        exit();
    }


    $votes[$ideaId][$userName] = $voteType;


    try {
        file_put_contents($votesFile, json_encode($votes, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    } catch (JsonException $e) {
        // Erreur lors de l'écriture dans le fichier JSON
        header("Location: display_ideas.php?error=json_write_error");
        exit();
    }


    // MAJ des votes en cumulation et validation (+ popup erreurs)

    $positiveVotes = 0;
    $negativeVotes = 0;
    foreach ($votes[$ideaId] as $voter => $vote) {
        if ($vote === 'positive') {
            $positiveVotes++;
        } elseif ($vote === 'negative') {
            $negativeVotes++;
        }
    }


    $ideas[$ideaIndex]['votes'] = ['positive' => $positiveVotes, 'negative' => $negativeVotes];


    try {
        file_put_contents($ideasFile, json_encode($ideas, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    } catch (JsonException $e) {
        header("Location: display_ideas.php?error=json_write_error");
        exit();
    }

    header("Location: display_ideas.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Boite à idées-vote</title>
</head>

<body>
    <h1>Boite à idée : votez !</h1>
    <p>Votez pour élire la meilleure idée proposée ci-dessous !</p>
    <?php
    // Idées classées des plus récentes aux plus anciennes (callback apply)
    usort($ideas, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    foreach ($ideas as $index => $idea) {
        $votes = $idea['votes'];
        $positiveVotes = $votes['positive'] ?? 0;
        $negativeVotes = $votes['negative'] ?? 0;
    }

    ?>
    <div>
        <h3><?php echo nl2br(htmlspecialchars($idea['description'])); ?></h3>
        <p><strong>Auteur :</strong> <?php echo htmlspecialchars($idea['author']); ?></p>
        <p><strong>Date :</strong> <?php echo htmlspecialchars($idea['date']); ?></p>
        <p><strong>Votes :</strong> Positifs: <?php echo $positiveVotes; ?> | Négatifs: <?php echo $negativeVotes; ?>
        </p>

        <form method="post">
            <input type="hidden" name="ideaIndex" value="<?php echo $index; ?>">
            <button type="submit" name="vote" value="positive">Vote positif</button>
            <button type="submit" name="vote" value="negative">Vote négatif</button>
        </form>
    </div>
    <hr>
</body>

</html>
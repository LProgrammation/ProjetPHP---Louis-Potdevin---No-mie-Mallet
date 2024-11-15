<?php

// USER : vérifie si le compte est connecté, au cas contraire, retourne à la page login.php

session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_authenticated']) {
    header("Location: sign-in.php");
    exit();
}

$userName = $_SESSION['user']['username'];

$ideasFile = '../../data/ideas.json';
$votesFile = '../../data/votes.json';
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
    if (isset($_POST['ideaId'])) {
        $ideaId = $_POST['ideaId'];
        $ideaIndex = $_POST['ideaIndex'];
        $ideaExist = false;
        foreach ($ideas as $idea) {

            if ($idea['id'] == $ideaId) {
                $ideaExist = true;
            }

        }
        if (!$ideaExist) {
            header("Location: votes-ideas.php?error=invalid_idea_id");
            exit();
        }
    } else {
        // Si l'index de l'idée est manquant ou invalide, redirection
        header("Location: votes-ideas.php?error=missing_idea_index");
        exit();
    }


    if (isset($_POST['vote']) && in_array($_POST['vote'], ['positive', 'negative'])) {
        $voteType = $_POST['vote'];
    } else {
        header("Location: votes-ideas.php?error=invalid_vote_type");
        exit();
    }


    // MAJ des votes en cumulation et validation (+ popup erreurs)
    $positiveVotes = 0;
    $negativeVotes = 0;

    // Vérification et enregistrement voté
    foreach ($votes as $index => $vote) {
        // si l'utlisateur a déjà voté pour une idée
        if (isset($vote[$userName])) {
            $prevId = $index;
            $prevVoteValue = $votes[$index][$userName];
            // On retire le vote de cette même idée

            // Ont parcours les différente idée pour trouver celle qui correspond à l'identifier de l'idée choisie (la nouvelle ou la même pour un changement de vote)
            foreach ($ideas as $index => $idea) {

                // Dans le cas d'un changement de vote dans la même idée
                if ($prevId == $ideaId) {

                    if ($voteType === "positive") {
                        $a = $ideas[$index]['votes']['positive'] + 1;
                        $b = $ideas[$index]['votes']['negative'];
                    } else if ($voteType === "negative") {
                        $a = $ideas[$index]['votes']['positive'];
                        $b = $ideas[$index]['votes']['negative'] - 1;
                    }
                    echo $a . " = " . $b;
                    $ideas[$index]['votes'] = ['positive' => $a, 'negative' => $b];
                    var_dump($ideas);
                }

            }
            unset($votes[$index][$userName]);

        } else {
            foreach ($ideas as $index => $idea) {
                if ($idea['id'] == $ideaId) {
                    if ($voteType == "positive")
                        $positiveVotes++;
                    if ($voteType == "negative")
                        $negativeVotes++;
                    $ideas[$index]['votes'] = ['positive' => $positiveVotes, 'negative' => $negativeVotes];
                }
            }

        }
        exit();
    }


    $votes[$ideaId][$userName] = $voteType;


    try {
        file_put_contents($votesFile, json_encode($votes, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    } catch (JsonException $e) {
        // Erreur lors de l'écriture dans le fichier JSON
        header("Location: votes-ideas.php?error=json_write_error");
        exit();
    }


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

    ?>
    <?php foreach ($ideas as $index => $idea) { ?>
        <div>

            <h3><?php echo nl2br(htmlspecialchars($idea['description'])); ?></h3>
            <p><strong>Auteur :</strong> <?php echo htmlspecialchars($idea['author']); ?></p>
            <p><strong>Date :</strong> <?php echo htmlspecialchars($idea['date']); ?></p>
            <p><strong>Votes :</strong> Positifs <?php echo $idea['votes']['positive']; ?> | Négatifs
                <?php echo $idea['votes']['negative']; ?>
            </p>

            <form method="post">
                <input type="hidden" name="ideaId" value="<?php echo $idea['id']; ?>">
                <input type="hidden" name="ideaIndex" value="<?php echo $index; ?>">
                <button type="submit" name="vote" value="positive">Vote positif</button>
                <button type="submit" name="vote" value="negative">Vote négatif</button>
            </form>
        </div>
    <?php } ?>
    <hr>
</body>

</html>
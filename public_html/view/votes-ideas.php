<?php
require "../controller/votes-ideas.php"
?>

<!DOCTYPE html>
<html>

<head>
    <title>Boite à idées-vote</title>
    <link rel="stylesheet" href="../styles/votesideas.css">

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
    <!-- Parcours de l'ensemble des idées enregister -->
    <?php foreach ($ideas as $index => $idea) { ?>
        <div>

            <h3><?php echo nl2br(htmlspecialchars($idea['titre'])); ?></h3>
            <h4><?php echo nl2br(htmlspecialchars($idea['description'])); ?></h4>
            <p><strong>Auteur :</strong> <?php echo htmlspecialchars($idea['author']); ?></p>
            <p><strong>Date :</strong> <?php echo htmlspecialchars($idea['date']); ?></p>
            <p><strong>Votes :</strong> Positifs: <?php echo $idea['votes']['positive']; ?> | Négatifs:
                <?php echo $idea['votes']['negative']; ?>
            </p>

            <form method="post">
                <input type="hidden" name="ideaId" value="<?php echo $ideas[$index]['id']; ?>">
                <input type="hidden" name="ideaIndex" value="<?php echo $index; ?>">
                <button type="submit" name="vote" value="positive">Vote positif</button>
                <button type="submit" name="vote" value="negative">Vote négatif</button>
            </form>

        </div>
    <?php } ?>
    <a href="sign-in.php"> Page de connexion</a>
    <a href="ideas.php"> Soumettre une idée</a>
    <hr>
</body>

</html>
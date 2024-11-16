<?php 
session_start();
if ( !$_SESSION['user']['is_authenticated']) {
    header("Location:sign-in.php");
    exit();
}

$userName = $_SESSION['user']['username'];

// Boucle pour sécuriser les entrées + historique des idées + création d'idées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['titre']) && !empty($_POST['titre'])) {
        
        
        $titre = trim($_POST['titre']);

        $titre = htmlspecialchars($titre, ENT_QUOTES, 'UTF-8');
        $description = trim($_POST['description']);

        $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    } else {
        header("Location: votes-ideas.php?error=empty_description");
        exit();
    }

    // Génération d'un identifiant unique pour l'idée
    $id = uniqid();
    $author = $userName;
    $date = date('Y-m-d H:i:s');
    $ideasFile = '../../data/ideas.json';
    $ideas = file_exists($ideasFile) ? json_decode(file_get_contents($ideasFile), true) : [];


    $newIdea = [
        'id' => $id,
        'titre' => $titre,
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
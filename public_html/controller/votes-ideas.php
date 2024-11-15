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


// Cette fonction permet de compter le nombre de vote pour chaque idées 

function countVoteTypeById($votes, $username, $id): array
{
    // Initialisation du voteList avec 0 en initial
    $voteList = ['negative' => 0, 'positive' => 0];
    // Parcours de l'ensemble des votes existant
    foreach ($votes as $index => $vote) {
        // Si l'index parcourus correspond à l'identifiant en paramètre
        if ($index == $id) {
                // Parcours de l'ensemble des utilisateurs enregistrer dans ce vote
                foreach($vote as $index => $item){
                    // Si l'utilisateur parcourus a voté négatif
                    if ($item === "negative") {
                        // On ajoute 1 au negative
                        $voteList['negative']++;
                    }
                    // Si l'utilisateur parcourus a voté positif
                    else if ($item === "positive") {
                        // On ajoute 1 au positive
                        $voteList['positive']++;
                    }
                }
            
        }
    }
    // Return la la liste finale
    return $voteList;

}
// Ajout du nombre de vote par idées
function setIdeaVoteValue(array $ideas, array $votes, string $username)
{
    // Parcours de la listes des idées
    foreach ($ideas as $index => $idea) {
        // Ajout du nombre de vote negative et positive dans l'idée parcourus via la fonction coutVoteTypeById
        $ideas[$index]['votes'] = countVoteTypeById($votes, $username, $idea['id']) ; 
    }
    // Return le resultat final ideas
    return $ideas;
}

// Permet de vérifier si l'utilisateur est déjà présent dans un vote
function getVoteExistValue(array $votes, string $username){
    // Parcours de l'ensemble de la liste des votes
    foreach($votes as $index => $vote){
        // Si le vote parcourus contient le nom de l'utilisateur passer en paramètre
        if(isset($vote[$username])){
            // On retourne l'identifiant de l'idée concernée ainsi que la valeur du vote de l'utilisateur
            return ['idIdeaVoted' => $index, 'voteValue' => $vote[$username]];
        }
    }
    // Si l'utilisateur n'a pas voté alors ont return null
    return null ; 
}


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


    
    // Affectation du resultat de la vérification d'existant d'un vote utilisateur dans la variable VoteExiste
    $VoteExist = getVoteExistValue($votes, $userName);

    // Si l'utilisateur connecter a déjà voté
    if(isset($VoteExist)){
        // On vérifie s'il vote sur la même idée
        if($VoteExist['idIdeaVoted'] == $ideaId){
            // S'il vote le même choix sur l'idée
            if($VoteExist['voteValue'] == $voteType){
                // On supprime sont vote (vote - 1) 
                unset($votes[$ideaId][$userName]);
            }
            else{
                // Sinon on modifie la valeur de son vote
                $votes[$ideaId][$userName] = $voteType ; 
            }
             
            
        }
        // Si ce n'est pas la même idée
        else{
            // On supprime le vote de l'idée précédente dont l'identifiant provient de la variable VoteExist['idIdeaVoted']
            // (référence à l'id du vote précédent)
            unset($votes[$VoteExist['idIdeaVoted']][$userName]); 
            // Ajout du nouveau vote dans l'idée choisie
            $votes[$ideaId][$userName] = $voteType ;
        }
    }
    // Si l'utilisateur n'a pas voté
    else{
        // On insère sont vote dans l'idée choisie (définie par ideaId)
        $votes[$ideaId][$userName] = $voteType ;
    }
    // On utilise la fonction setIdeaVoteValue pour compter le nombre de vote pas idée 
    // et l'insérer dans les données de chaques idée pour l'affichage frontend
    $ideas = setIdeaVoteValue($ideas, $votes, $userName);

    // Insertion des votes dans le fichiers de données des votes.
    try {
        file_put_contents($votesFile, json_encode($votes, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    } catch (JsonException $e) {
        // Erreur lors de l'écriture dans le fichier JSON
        header("Location: votes-ideas.php?error=json_write_error");
        exit();
    }
    // Insertion des idées dans le fichiers de données des idées.
    try {
        file_put_contents($ideasFile, json_encode($ideas, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    } catch (JsonException $e) {
        header("Location: votes-ideas.php?error=json_write_error");
        exit();
    }
    header("Location: votes-ideas.php");
    exit();


}

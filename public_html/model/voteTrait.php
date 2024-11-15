<!-- vote -->

<?php

Trait Vote{
    protected $pdo; // Déclaration de la propriété PDO dans le trait

    public function setPDO(PDO $pdo) {
        $this->pdo = $pdo; // Méthode pour définir PDO
    }

    public function saveVote($user_id, $idea_id) {
        $stmt = $this->pdo->prepare("INSERT INTO vote(idea_id, vote_id) VALUES (:ideaId, :userId)");
        $stmt->bindParam(":ideaId", $idea_id) ;
        $stmt->bindParam(":userId", $user_id) ;
         
    }
}
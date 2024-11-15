<!-- Ideas -->
<?php
Trait Ideas{
    protected $pdo; // Déclaration de la propriété PDO dans le trait

    public function setPDO(PDO $pdo) {
        $this->pdo = $pdo; // Méthode pour définir PDO
    }

    public function saveIdea($subject, $content, $authorId) {
        $stmt = $this->pdo->prepare("INSERT INTO ideas(subject, content, author_id) VALUES (:ideaSubject, :ideaContent, :ideaAuthorId)");
        $stmt->bindParam(":ideaSubject", $subject) ;
        $stmt->bindParam(":ideaContent", $content) ;
        $stmt->bindParam(":ideaAuthorId", $authorId) ;
        $stmt->execute() ;
    }
}
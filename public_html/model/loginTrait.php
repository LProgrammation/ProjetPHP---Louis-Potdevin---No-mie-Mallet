<!-- login -->
<?php
Trait Users{
    protected $pdo; // Déclaration de la propriété PDO dans le trait

    public function setPDO(PDO $pdo) {
        $this->pdo = $pdo; // Méthode pour définir PDO
    }

    public function checkUser($pseudo) {
        $stmt = $this->pdo->prepare("SELECT id, pseudo FROM users WHERE pseudo=:pseudo");
        $stmt->bindParam(":pseudo", $pseudo) ;
         $stmt->execute() ;
         $res = $stmt->fetch(PDO::FETCH_ASSOC) ;
        if ($res) {

         return $res ;
        }
        return false ; 
    }
}
<!-- login -->
<?php
Trait Users{
    protected $pdo; // Déclaration de la propriété PDO dans le trait

    public function setPDO(PDO $pdo) {
        $this->pdo = $pdo; // Méthode pour définir PDO
    }
}
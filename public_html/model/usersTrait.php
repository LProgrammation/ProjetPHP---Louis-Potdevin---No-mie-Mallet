<!-- login -->
<?php
trait Users
{
    protected $pdo; // Déclaration de la propriété PDO dans le trait

    public function setPDO(PDO $pdo)
    {
        $this->pdo = $pdo; // Méthode pour définir PDO
    }

    public function register($username, $password): bool|string
    {

        try {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO users(username, password) VALUES(:username, :password)");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->execute();
            return 'success';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "duplicate";
            } // Return the exception message for other errors 
            return $e->getMessage();
        }

    }

    public function checkLogin($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT id, username, password FROM users WHERE username=:username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($res) {

            if (password_verify($password, $res['password']))
                return $res;
            return false;

        }
        return false;
    }
}
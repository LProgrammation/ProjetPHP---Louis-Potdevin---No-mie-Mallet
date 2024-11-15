<!-- Connection to database -->

<?php
/*
? Lien d'aide à la gestion de la base de données mariadb en PDO : 
! https://mariadb.com/resources/blog/developer-quickstart-php-data-objects-and-mariadb/

*/

require "ideasTrait.php" ; 
require "loginTrait.php" ; 
require "voteTrait.php" ; 

class BDD {
    use Ideas, Users, Vote {
        Ideas::setPDO as setIdeasPDO; 
        Users::setPDO as setUsersPDO; 
        Vote::setPDO as setVotePDO; 
        Ideas::setPDO insteadof Users, Vote;
    }
   
    protected $pdo; // Propriété PDO dans la classe BDD

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo; // Initialisation de la propriété PDO
        $this->setIdeasPDO($pdo);
        $this->setUsersPDO($pdo);
        $this->setVotePDO($pdo);
        
    }
}

$db = "PHP_LP_NM"; 
$host = "localhost";
$user = "admin";
$pwd = "admin";  

$dsn = "mysql:host=".$host.";dbname=".$db.";charset=utf8mb4";

$options = [
  PDO::ATTR_EMULATE_PREPARES   => false, // * Disable emulation mode for "real" prepared statements
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // * Disable errors in the form of exceptions
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // * Make the default fetch be an associative array
];

try {
  $pdo = new PDO($dsn, $user, $pwd, $options);
} 
catch (Exception $e) {
  error_log($e->getMessage());
  exit('Something bad happened'); 
}

$bdd = new BDD($pdo);
$tests = $bdd->checkUser("Louis");
if($tests) {
  print_r($tests);
}
else{
  echo "Utilisateur n'existe pas ! ";
};
<?php
session_start();
require "../model/BDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["sign-in"])) {
        $signIn = $bdd->checkLogin(username: $_POST["username"], password: $_POST['password']);
        if ($signIn) {
            try {
                $userId = $signIn['id'];
                $session_id = $_COOKIE['sessionId'] ; 
                $_SESSION['user']['is_authenticated'] = true;
                $_SESSION['user']['username'] =  $_POST["username"];
                $_SESSION['user']['userid'] = $userId;
                $_SESSION['user']['session_id'] = $session_id;
                
                header('Location:ideas.php');
                exit();
            } catch (Exception $e) {
                echo "" . $e->getMessage() . "";
            }
        } else {
            echo "echec connexion !";
        }


    } else if (isset($_POST["sign-up"])) {

        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $bdd->register(username: $_POST["username"], password: $_POST['password']);
    }
}
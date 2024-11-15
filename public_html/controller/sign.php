<?php
session_start();
require "../model/BDD.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["sign-in"])) {

        

        $signIn = $bdd->checkLogin(username: $_POST["username"], password: $_POST['password']);
        if ($signIn) {
            try {
                $userId = $signIn['id'];
                var_dump($_COOKIE['sessionId']);
                $session_id = $_COOKIE['sessionId'] ; 
                $_SESSION['is_authenticated '] = true;
                $_SESSION['username '] =  $_POST["username"];
                $_SESSION['userid'] = $userId;
                $_SESSION['session_id'] = $session_id;
                echo $session_id , ' = ', $_POST["username"],' = ', $userId,'';
                header('Location:ideas.php');
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
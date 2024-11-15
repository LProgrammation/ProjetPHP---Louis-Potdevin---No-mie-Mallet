<?php 
session_start();
if(isset($_COOKIE['sessionId'])) $session_id =  $_COOKIE['sessionId'] ; 
echo ''.$session_id.'';
if (!isset($session_id) || !$_SESSION[$session_id]["is_authenticated"]) {
    header("Location:sign-in.php");
    exit();
}
else{
    header("Location:ideas.php");
    exit();
}
<?php
session_start();
$idSession = session_id();
if ($_SESSION[$idSession]["is_authenticated"]) {
    header("Location:sign-in.php");
    exit();
}
else{
    header("Location:ideas.php");
    exit();
}
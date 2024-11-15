<?php
session_start();
if (!$_SESSION['user']["is_authenticated"]) {
    header("Location:sign-in.php");
    exit();
}
else{
    header("Location:ideas.php");
    exit();
}
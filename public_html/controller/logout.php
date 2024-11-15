<?php

session_start();
session_destroy();

header("Location:../view/sign-in.php");
exit();
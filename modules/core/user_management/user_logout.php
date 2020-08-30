<?php

session_start();

$_SESSION['logged_in'] = false;
$_SESSION['user_data'] = null;
$_SESSION['user_access'] = null;
header("Location: /login.php");

?>
<?php
session_start();

include 'header.php';

$lgnObject = new Login();
$lgnObject->logout();

header('Location: index.php');
?>

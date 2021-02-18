<?php
session_start();
if(time() - $_SESSION['timestamp'] > 900) { //subtract new timestamp from the old one
    echo"<script>alert('15 Minutes over!');</script>";
    header("location: login.php");
    exit;
} else {
    $_SESSION['timestamp'] = time(); //set new timestamp
}

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include_once "WebHelper.php";
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Dansk Ejendomsforvaltning - Klagesystem</title>
</head>


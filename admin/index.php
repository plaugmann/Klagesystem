<?php

session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
if(!isset($_SESSION["user.role"]) || $_SESSION["user.role"] !== "Admin"){
    header("location: ../login.php");
    exit;
}
include_once "../WebHelper.php";
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="admin_styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Dansk Ejendomsforvaltning - Klagesystem - Administration</title>
</head>
<body>
<div>
    <div class="w3-container w3-center">
        <?
        echo "<h1>Velkommen " . $_SESSION["user.name"] . "</h1>";
        ?>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center">
            <p>Her kan du administrere følgende</p>
            <ul>
                <li><button id="btnFields">Felter</button></li>
                <li><button id="btnUsers">Brugere</button></li>
                <li><button id="btnProperties">Ejendomme</button></li>
            </ul>
        </div>
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
    </div>
</div>
<p><button id="btnLogout">Logout</button></p>
<script>
    $(document).ready(function(){
        $("#btnLogout").click(function(){
            $(location).attr('href', '../logout.php')
        });
        $("#btnFields").click(function(){
            $(location).attr('href', 'fields.php')
        });
    });
</script>
</body>

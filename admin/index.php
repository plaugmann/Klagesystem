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
include_once "../header.php";
?>
</head>
<body>
<?
    echo "<h1>Welcome to admin: " . $_SESSION["user.name"] . "</h1>";
?>
<p><button>Logout</button></p>
<script>
    $(document).ready(function(){
        $("button").click(function(){
            $(location).attr('href', '../logout.php')
        });
    });
</script>
</body>

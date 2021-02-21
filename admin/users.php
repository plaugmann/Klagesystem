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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST["ID"]);
    $action = trim($_POST["Action"]);

    if( $action == "del" and $id != "" ) {

        $errorMsg = User::deleteUser($id);
        if($errorMsg != "True") {
            echo "<script>alert('Fejl under sletningen: ". $errorMsg ."');</script>";
        }
    }
}

?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="admin_styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <title>Dansk Ejendomsforvaltning - Klagesystem - Administration</title>
</head>
<body>
<div>
    <div class="w3-container w3-center">
        <?
        echo "<h1>Brugere i systemet</h1>";

        ?>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center transparentBg">
            <p>Klik på en af brugerne for at redigere</p>
            <ul>
                <?
                    User::getUsers();
                ?>
                <li><button id="btnNewField">Ny bruger</button></li>
            </ul>
            <ul>
                <li><button id="btnBack"><-- Tilbage</button></li>
            </ul>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="actionForm" method="POST">
                <input name="ID" ID="ID" value="" type="hidden" />
                <input name="Action" ID="Action" value="" type="hidden" />
            </form>
        </div>
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
    </div>
</div>
<p><button id="btnLogout">Logout</button></p>
<script>
    function ConfirmDialog(message, id) {
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true,
                title: 'Slet bruger?',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                    Yes: function() {
                        $(this).dialog("close");
                        $("#ID").val(id);
                        $("#Action").val("del");
                        $("#actionForm").submit();
                        return true;
                    },
                    No: function() {
                        $(this).dialog("close");
                        return false;
                    }
                },
                close: function(event, ui) {
                    $(this).remove();
                }
            });
    };

    $(document).ready(function(){
        $("#btnLogout").click(function(){
            $(location).attr('href', '../logout.php')
        });
        $("#btnBack").click(function(){
            $(location).attr('href', 'index.php')
        });
        $("#btnNewField").click(function(){
            $(location).attr('href', 'editUser.php?new=1')
        });
        $(".deleteButton").click(function(){

            var data = $(this).attr('href').split("#id=");
            ConfirmDialog("Er du sikker på at du vil slette: " + data[0].trim(), data[1].trim())
            return false;
        });
    });
</script>
</body>

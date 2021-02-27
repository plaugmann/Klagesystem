<?php

session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../logout.php");
    exit;
}
if(!isset($_SESSION["user.role"]) || $_SESSION["user.role"] !== "Admin"){
    header("location: ../login.php");
    exit;
}
include_once "../WebHelper.php";

$isNew = false;
$user =  new User(-1, "", null,"","","User");
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);
        $user = User::Load((int) $id);
    }
    if (!empty(trim($_GET["new"]))) {
        $isNew = true;
    }

}

$saveMessage = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST["FieldID"]);
    $action = trim($_POST["Action"]);

    if( $action == "del" and $id != "" ) {

        $errorMsg = User::deleteUser($id);
        if($errorMsg != "True") {
            echo "<script>alert('Fejl under sletningen: ". $errorMsg ."');</script>";
        }
        else {
            header("location: users.php");
            exit;
        }
    }
    elseif( $action == "save" and $id != "" ) {

        $user = User::Load((int) $id) ;

        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $name = trim($_POST["nameField"]);
        $email = trim($_POST["email"]);
        $role = trim($_POST["role"]); //USERROLE

        $user->username = $username;
        if ($password != "")
            $user->password = $password;
        $user->name = $name;
        $user->email = $email;
        $user->role = $role;

        if ($user->Save() != 0)
            $saveMessage = 'Ændringerne blev gemt';
        else
            $saveMessage = 'Ingen ændringer blev registreret';
    }
    elseif ( $action == "create") {

        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $name = trim($_POST["nameField"]);
        $email = trim($_POST["email"]);
        $role = trim($_POST["role"]);

        $user->username = $username;
        $user->password = $password;
        $user->name = $name;
        $user->email = $email;
        $user->role = $role;

        if ($user->Insert() != -1)
            $saveMessage = $name . " blev oprettet";
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
        if ($isNew)
            echo "<h1>Opret ny bruger</h1>";
        else
            echo "<h1>Redigér ". $user->name ."</h1>";

        ?>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center transparentBg">
            <p>Herunder kan du redigere brugeren:</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="actionForm" method="POST">
                <table>
                    <tr>
                        <th>Navn:</th>
                        <td><input type="text" class="txtBox" name="nameField" id="nameField" value="<? echo $user->name; ?>"></td>
                    </tr>
                    <tr>
                        <th>Brugernavn:</th>
                        <td><input type="text" class="txtBox" name="username" id="username" value="<? echo $user->username; ?>"></td>
                    </tr>
                    <tr>
                        <th>Kodeord:</th>
                        <td><br/>
                            <label for="password">Skriv kodeord:</label><br/><input type="password" class="txtBox" name="password" id="password" value=""><br/>
                            <label for="passwordRetype">Kodeord igen:</label><br/><input type="password" class="txtBox" name="passwordRetype" id="passwordRetype" value=""><br/>
                        </td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><input type="text" class="txtBox" name="email" id="email" value="<? echo $user->email; ?>"></td>
                    </tr>

                    <tr>
                        <th>Rolle:</th>
                        <td>
                            <?
                            if ($user->role == "Sales")
                                echo "<input type='radio' id='roleSales' name='role' value='Sales' checked>";
                            else
                                echo "<input type='radio' id='roleSales' name='role' value='Sales'>";
                            ?>
                            <label for="roleSales">Normal bruger</label><br/>

                            <?
                            if ($user->role == "User")
                                echo "<input type='radio' id='roleUser' name='role' value='User' checked>";
                            else
                                echo "<input type='radio' id='roleUser' name='role' value='User'>";
                            ?>
                            <label for="roleUser">Superbruger</label><br/>

                            <?
                            if ($user->role == "Admin")
                                echo "<input type='radio' id='roleAdmin' name='role' value='Admin' checked>";
                            else
                                echo "<input type='radio' id='roleAdmin' name='role' value='Admin'>";
                            ?>
                            <label for="roleUser">Administrator</label><br/>
                            <br/>
                        </td>
                    </tr>
                    <?
                    if ($saveMessage != "") {?>
                        <tr id="statusRow">
                            <th>&nbsp;</th>
                            <td style="color: red;"><? echo $saveMessage;?></td>
                        </tr>

                        <?
                    }
                    ?>
                </table>
                <input name="FieldID" ID="FieldID" value="<? echo $user->id; ?>" type="hidden" />
                <input name="Action" ID="Action" value="" type="hidden" />
                <?
                if ($isNew) {
                    ?>
                    <input type="submit" id="btnCreate" name="btnCreate" value="Operet bruger" />
                    <?
                }
                else {
                    ?>
                    <input type="submit" id="btnSave" name="btnSave" value="Gem ændringer" />&nbsp;<a href="<? echo $user->name ."#id=". $user->id; ?>" class='deleteButton' title='Slet <? echo $user->name; ?>'><img src='delete_icon.png' title='Slet <? echo $user->name; ?>'></a>
                    <?
                }
                ?>
            </form>
            <ul>
                <li><button id="btnBack"><-- Tilbage</button></li>
            </ul>

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
    }
    function ShowError(message, func) {
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '</h6></div>')
            .dialog({
                modal: true,
                title: 'Fejl i validering',
                zIndex: 10000,
                autoOpen: true,
                width: 'auto',
                resizable: false,
                buttons: {
                    Ok: func
                },
                close: function(event, ui) {
                    $(this).remove();
                }
            });
    }

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    $(document).ready(function(){
        $("#name").focus();
        $("#statusRow").delay(2000).fadeOut('slow');
        $("#btnLogout").click(function(){
            $(location).attr('href', '../logout.php')
        });
        $("#btnBack").click(function(){
            $(location).attr('href', 'users.php')
        });
        $("#btnSave").click(function(event){

            if ($("#password").val() != $("#passwordRetype").val()) {
                ShowError("Kodeordene stemmer ikke. Prøv igen", function() {
                    $(this).dialog("close");
                    $("#password").val("").focus();
                    $("#passwordRetype").val("");
                    return false;
                });
                event.preventDefault();
                return false;
            }
            if (!isEmail($("#email").val())) {
                ShowError("Emailen er ugyldig", function() {
                    $(this).dialog("close");
                    $("#email").val("").focus();
                    return false;
                });
                event.preventDefault();
                return false;

            }
            $("#Action").val("save");
        });
        $("#btnCreate").click(function(){
            $("#Action").val("create");
        });
        $("#description").resizable();
        $(".deleteButton").click(function(){

            var data = $(this).attr('href').split("#id=");
            ConfirmDialog("Er du sikker på at du vil slette: " + data[0].trim(), data[1].trim())
            return false;
        });
    });
</script>
</body>

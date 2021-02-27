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
$field = new Field(-1, "","", "",FieldStatus::ACTIVE, RenderGroup::GROUP_A, ContentType::SINGLE_TEXT);
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);
        $field = Field::Load((int)$id);
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

        $helper = new WebHelper();
        $errorMsg =$helper->deleteField($id);
        if($errorMsg != "True") {
            echo "<script>alert('Fejl under sletningen: ". $errorMsg ."');</script>";
        }
        else {
            header("location: fields.php");
            exit;
        }
    }
    elseif( $action == "save" and $id != "" ) {

        $field = Field::Load((int)$id);

        $name = trim($_POST["fieldName"]);
        $description = trim($_POST["description"]);
        $autofill = trim($_POST["autoFill"]);
        $status = FieldStatus::FromValue(trim($_POST["status"]));
        $renderGroup = trim($_POST["renderGroup"]);
        $contentType = trim($_POST["contentType"]);

        $field->name = $name;
        $field->description = $description;
        $field->autoFill = $autofill;
        $field->status = $status;
        $field->renderGroup = $renderGroup;
        $field->contentType = $contentType;

        if ($field->Save() != 0)
            $saveMessage = 'Ændringer blev gemt';
        else
            $saveMessage = 'Ingen ændringer blev registreret';
    }
    elseif ( $action == "create") {

        $name = trim($_POST["fieldName"]);
        $description = trim($_POST["description"]);
        $autofill = trim($_POST["autoFill"]);
        $status = FieldStatus::FromValue(trim($_POST["status"]));
        $renderGroup = trim($_POST["renderGroup"]);
        $contentType = trim($_POST["contentType"]);

        $field->name = $name;
        $field->description = $description;
        $field->autoFill = $autofill;
        $field->status = $status;
        $field->renderGroup = $renderGroup;
        $field->contentType = $contentType;

        if ($field->Insert() != -1)
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
            echo "<h1>Opret nyt felt</h1>";
        else
            echo "<h1>Redigér ". $field->name ."</h1>";

        ?>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center transparentBg">
            <p>Herunder kan du redigere feltet:</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="actionForm" method="POST">
            <table>
                <tr>
                    <th>Navn:</th>
                    <td><input type="text" class="txtBox" name="fieldName" id="fieldName" value="<? echo $field->name; ?>"</td>
                </tr>
                <tr>
                    <th>Beskrivelse:</th>
                    <td><textarea cols="70" rows="10" id="description" name="description"><? echo $field->description; ?></textarea></td>
                </tr>
                <tr>
                    <th>Auto fillout:</th>
                    <td><input type="text" class="txtBox" name="autoFill" id="autoFill" value="<? echo $field->autoFill; ?>"</td>
                </tr>
                <tr>
                    <th>Visningsgruppe</th>
                    <td>
                        <?

                        $chkBoxA = "";
                        $chkBoxB = "";
                        $chkBoxC = "";
                        $chkBoxD = "";
                        $chkBoxE = "";
                        $chkBoxF = "";
                        $chkBoxG = "";

                        switch ($field->renderGroup) {
                            case RenderGroup::GROUP_A :
                                $chkBoxA = "selected";
                                break;
                            case RenderGroup::GROUP_B :
                                $chkBoxB = "selected";
                                break;
                            case RenderGroup::GROUP_C :
                                $chkBoxC = "selected";
                                break;
                            case RenderGroup::GROUP_D :
                                $chkBoxD = "selected";
                                break;
                            case RenderGroup::GROUP_E :
                                $chkBoxE = "selected";
                                break;
                            case RenderGroup::GROUP_F :
                                $chkBoxF = "selected";
                                break;
                            case RenderGroup::GROUP_G :
                                $chkBoxG = "selected";
                                break;
                        }
                        ?>
                        <br/>
                        <label for="renderGroup">Vælg hvor på siden, at dette felt skal vises:</label><br/>
                        <select name="renderGroup" id="renderGroup">
                            <option value="A" <? echo $chkBoxA; ?>>Gruppe A - dvs øverst på siden</option>
                            <option value="B" <? echo $chkBoxB; ?>>Gruppe B</option>
                            <option value="C" <? echo $chkBoxC; ?>>Gruppe C</option>
                            <option value="D" <? echo $chkBoxD; ?>>Gruppe D</option>
                            <option value="E" <? echo $chkBoxE; ?>>Gruppe E</option>
                            <option value="F" <? echo $chkBoxF; ?>>Gruppe F</option>
                            <option value="G" <? echo $chkBoxG; ?>>Gruppe G - dvs nederst på siden</option>
                        </select>
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <th>Felt type:</th>
                    <td>
                        <?
                        if ($field->contentType == ContentType::SINGLE_TEXT)
                            echo "<input type='radio' id='contentTypeSingle' name='contentType' value='Single_Text' checked>";
                        else
                            echo "<input type='radio' id='contentTypeSingle' name='contentType' value='Single_Text'>";
                        ?>
                        <label for="contentTypeSingle">Enkelt-linje tekst</label><br/>

                        <?
                        if ($field->contentType == ContentType::MULTI_TEXT)
                            echo "<input type='radio' id='contentTypeMulti' name='contentType' value='Multi_Text' checked>";
                        else
                            echo "<input type='radio' id='contentTypeMulti' name='contentType' value='Multi_Text'>";
                        ?>
                        <label for="contentTypeMulti">Flere linjers tekst</label><br/>

                        <?
                        if ($field->contentType == ContentType::NUMBER)
                            echo "<input type='radio' id='contentTypeNumber' name='contentType' value='Number' checked>";
                        else
                            echo "<input type='radio' id='contentTypeNumber' name='contentType' value='Number'>";
                        ?>
                        <label for="contentTypeMulti">Tal</label><br/>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        <?
                            if ($field->status == FieldStatus::ACTIVE)
                                echo "<input type='radio' id='statusActive' name='status' value='Active' checked>";
                            else
                                echo "<input type='radio' id='statusActive' name='status' value='Active'>";
                        ?>
                        <label for="statusActive">Aktiv</label><br/>

                        <?
                        if ($field->status == FieldStatus::INACTIVE)
                            echo "<input type='radio' id='statusInactive' name='status' value='Inactive' checked>";
                        else
                            echo "<input type='radio' id='statusInactive' name='status' value='Inactive'>";
                        ?>
                        <label for="statusInactive">Inaktiv</label><br/>
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
                <input name="FieldID" ID="FieldID" value="<? echo $field->id; ?>" type="hidden" />
                <input name="Action" ID="Action" value="" type="hidden" />
                <?
                if ($isNew) {
                ?>
                    <input type="submit" id="btnCreate" name="btnCreate" value="Operet felt" />
                <?
                }
                else {
                ?>
                    <input type="submit" id="btnSave" name="btnSave" value="Gem ændringer" />&nbsp;<a href="<? echo $field->name ."#id=". $field->id; ?>" class='deleteButton' title='Slet <? echo $field->name; ?>'><img src='delete_icon.png' title='Slet <? echo $field->name; ?>'></a>
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
                title: 'Slet felt?',
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

    $(document).ready(function(){
        $("#name").focus();
        $("#statusRow").delay(2000).fadeOut('slow');
        $("#btnLogout").click(function(){
            $(location).attr('href', '../logout.php')
        });
        $("#btnBack").click(function(){
            $(location).attr('href', 'fields.php')
        });
        $("#btnSave").click(function(){
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

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
$property = new Property(-1,"", "","","","","","",-1,-1, PropertyUsage::FABRIK, PropertyStatus::WAITING, -1 );
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);
        $property = Property::Load((int)$id);
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
        $errorMsg =Property::deleteProperty($id);
        if($errorMsg != "True") {
            echo "<script>alert('Fejl under sletningen: ". $errorMsg ."');</script>";
        }
        else {
            header("location: fields.php");
            exit;
        }
    }
    elseif( $action == "save" and $id != "" ) {

        $property = Property::Load((int)$id);

        $zip = trim($_POST["zip"]);
        $city = trim($_POST["city"]);
        $road = trim($_POST["road"]);
        $houseNo = trim($_POST["houseNo"]);
        $dinGeoLink = trim($_POST["dinGeoLink"]);
        $residentName = trim($_POST["residentName"]);
        $residentCVR = trim($_POST["residentCVR"]);
        $valuation = (int) trim($_POST["valuation"]);
        $coverage = (int) trim($_POST["coverage"]);
        $propUsage = PropertyUsage::FromValue(trim($_POST["propUsage"]));
        $status = PropertyStatus::FromValue(trim($_POST["status"]));
        $responsible = trim($_POST["responsible"]);

        $property->zip = $zip;
        $property->city = $city;
        $property->road = $road;
        $property->houseNo = $houseNo;
        $property->dinGeoLink = $dinGeoLink;
        $property->residentName = $residentName;
        $property->residentCVR = $residentCVR;
        $property->valuation = $valuation;
        $property->coverage = $coverage;
        $property->propUsage = $propUsage;
        $property->status = $status;
        $property->responsible = $responsible;

        if ($property->Save() != 0)
            $saveMessage = 'Ændringer blev gemt';
        else
            $saveMessage = 'Ingen ændringer blev registreret';
    }
    elseif ( $action == "create") {

        $zip = trim($_POST["zip"]);
        $city = trim($_POST["city"]);
        $road = trim($_POST["road"]);
        $houseNo = trim($_POST["houseNo"]);
        $dinGeoLink = trim($_POST["dinGeoLink"]);
        $residentName = trim($_POST["residentName"]);
        $residentCVR = trim($_POST["residentCVR"]);
        $valuation = (int) trim($_POST["valuation"]);
        $coverage = (int) trim($_POST["coverage"]);
        $propUsage = PropertyUsage::FromValue(trim($_POST["propUsage"]));
        $status = PropertyStatus::FromValue(trim($_POST["status"]));
        $responsible = trim($_POST["responsible"]);

        $property->zip = $zip;
        $property->city = $city;
        $property->road = $road;
        $property->houseNo = $houseNo;
        $property->dinGeoLink = $dinGeoLink;
        $property->residentName = $residentName;
        $property->residentCVR = $residentCVR;
        $property->valuation = $valuation;
        $property->coverage = $coverage;
        $property->propUsage = $propUsage;
        $property->status = $status;

        if ($property->Insert() != -1)
            $saveMessage = $road . " " . $houseNo . " blev oprettet";
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
            echo "<h1>Opret ny ejendom</h1>";
        else
            echo "<h1>Redigér ". $property->road . " " . $property->houseNo . "</h1>";

        ?>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center transparentBg">
            <p>Herunder kan du redigere ejendommen:</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="actionForm" method="POST">
                <table>
                    <tr>
                        <th>Vejnavn:</th>
                        <td><input type="text" class="txtBox" name="road" id="road" value="<? echo $property->road; ?>"></td>
                    </tr>
                    <tr>
                        <th>Vejnummer:</th>
                        <td><input type="text" class="txtBox" name="houseNo" id="houseNo" value="<? echo $property->houseNo; ?>"></td>
                    </tr>
                    <tr>
                        <th>Postnr:</th>
                        <td><input type="text" class="txtBox" name="zip" MAXLENGTH="5" id="zip" value="<? echo $property->zip; ?>"></td>
                    </tr>
                    <tr>
                        <th>By:</th>
                        <td><input type="text" class="txtBox" name="city" id="city" value="<? echo $property->city; ?>"></td>
                    </tr>
                    <tr>
                        <th>DinGeo:</th>
                        <td><input type="text" class="txtBox" name="dinGeoLink" id="dinGeoLink" value="<? echo $property->dinGeoLink; ?>"><br/>
                            <a href="<? echo $property->dinGeoLink; ?>" target="_blank">Test link</a><br /> </td>
                    </tr>
                    <tr>
                        <th>Firmanavn:</th>
                        <td><input type="text" class="txtBox" name="residentName" id="residentName" value="<? echo $property->residentName; ?>"></td>
                    </tr>
                    <tr>
                        <th>CVR:</th>
                        <td><input type="text" class="txtBox" name="residentCVR" id="residentCVR" MAXLENGTH="10" value="<? echo $property->residentCVR; ?>"></td>
                    </tr>
                    <tr>
                        <th>Vurdering:</th>
                        <td><input type="text" class="txtBox" name="valuation" id="valuation" value="<? echo $property->valuation; ?>"></td>
                    </tr>
                    <tr>
                        <th>Dækningsafgift:</th>
                        <td><input type="text" class="txtBox" name="coverage" id="coverage" value="<? echo $property->coverage; ?>"></td>
                    </tr>
                    <tr>
                        <th>Anvendelse</th>
                        <td>
                            <?


                            $chkBoxEE = "";
                            $chkBoxFAPF = "";
                            $chkBoxFA = "";
                            $chkBoxPR = "";
                            $chkBoxFOPF = "";
                            $chkBoxFO = "";
                            $chkBoxST = "";

                            switch ($property->propUsage) {
                                case PropertyUsage::ERHVERVSEJENDOM :
                                    $chkBoxEE = "selected";
                                    break;
                                case PropertyUsage::FABRIK_PAA_FREMMED :
                                    $chkBoxFAPF = "selected";
                                    break;
                                case PropertyUsage::FABRIK :
                                    $chkBoxFA = "selected";
                                    break;
                                case PropertyUsage::PRIVAT :
                                    $chkBoxPR = "selected";
                                    break;
                                case PropertyUsage::FORRETNING_PAA_FREMMED:
                                    $chkBoxFOPF = "selected";
                                    break;
                                case PropertyUsage::FORRETNING :
                                    $chkBoxFO = "selected";
                                    break;
                                case PropertyUsage::STATSEJENDOM :
                                    $chkBoxST = "selected";
                                    break;
                            }

                            ?>
                            <br/>
                            <label for="propUsage">Vælg den bedste beskrivelse for anvendelse af ejendommen:</label><br/>
                            <select name="propUsage" id="propUsagep">
                                <option value="Erhvervsejendom af speciel karakter" <? echo $chkBoxEE; ?>>Erhvervsejendom af speciel karakter</option>
                                <option value="Fabrik og lager på fremmed grund." <? echo $chkBoxFAPF; ?>>Fabrik og lager på fremmed grund.</option>
                                <option value="Fabrik og lager." <? echo $chkBoxFA; ?>>Fabrik og lager.</option>
                                <option value="Privat institutions- og serviceejendom." <? echo $chkBoxPR; ?>>Privat institutions- og serviceejendom.</option>
                                <option value="Ren forretning på fremmed grund." <? echo $chkBoxFOPF; ?>>Ren forretning på fremmed grund.</option>
                                <option value="Ren forretning." <? echo $chkBoxFO; ?>>Ren forretning.</option>
                                <option value="Statsejendom (Bebygget)." <? echo $chkBoxST; ?>>Statsejendom (Bebygget).</option>
                            </select>
                            <br/><br/>
                        </td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?
                            if ($property->status == PropertyStatus::WAITING)
                                echo "<input type='radio' id='statusWaiting' name='status' value='Waiting' checked>";
                            else
                                echo "<input type='radio' id='statusWaiting' name='status' value='Waiting'>";
                            ?>
                            <label for="statusWaiting">Afventer</label><br/>

                            <?
                            if ($property->status == PropertyStatus::COMPLETE)
                                echo "<input type='radio' id='statusComplete' name='status' value='Complete' checked>";
                            else
                                echo "<input type='radio' id='statusComplete' name='status' value='Complete'>";
                            ?>
                            <label for="statusComplete">Afsluttet</label><br/>

                            <?
                            if ($property->status == PropertyStatus::CANCELED)
                                echo "<input type='radio' id='statusCanceled' name='status' value='Canceled' checked>";
                            else
                                echo "<input type='radio' id='statusCanceled' name='status' value='Canceled'>";
                            ?>
                            <label for="statusCanceled">Afvist</label><br/>

                            <?
                            if ($property->status == PropertyStatus::IN_PROGRESS)
                                echo "<input type='radio' id='statusInProgress' name='status' value='In_Progress' checked>";
                            else
                                echo "<input type='radio' id='statusInProgress' name='status' value='In_Progress'>";
                            ?>
                            <label for="statusInProgress">Igangværende</label><br/>
                        </td>
                    </tr>
                    <tr>
                        <th>Sagsbehandler:</th>
                        <td>
                            <select id="responsible" name="responsible">
                                <?
                                    User::getResponsible($property->responsible);
                                ?>
                            </select>
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
                <input name="FieldID" ID="FieldID" value="<? echo $property->id; ?>" type="hidden" />
                <input name="Action" ID="Action" value="" type="hidden" />
                <?
                if ($isNew) {
                    ?>
                    <input type="submit" id="btnCreate" name="btnCreate" value="Operet ejendom" />
                    <?
                }
                else {
                    ?>
                    <input type="submit" id="btnSave" name="btnSave" value="Gem ændringer" />&nbsp;<a href="<? echo $property->road ."#id=". $property->id; ?>" class='deleteButton' title='Slet <? echo $property->road . " " .$property->houseNo; ?>'><img src='delete_icon.png' title='Slet <? echo $property->road . " " .$property->houseNo; ?>'></a>
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
                title: 'Slet ejendom?',
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

    function formatNumber(obj) {
        var n = parseInt(obj.val().replace(/\D/g,''),10);
        obj.val(n.toLocaleString());
    }

    $(document).ready(function(){
        $("#name").focus();
        $("#statusRow").delay(2000).fadeOut('slow');
        $("#btnLogout").click(function(){
            $(location).attr('href', '../logout.php')
        });
        $("#btnBack").click(function(){
            $(location).attr('href', 'properties.php')
        });
        $("#btnSave").click(function(){

            $("#valuation").val(parseInt($("#valuation").val().replace(/\D/g,''),10));
            if (!$.isNumeric($("#valuation").val())) {
                ShowError("Ejendomsvurdering er ugyldig", function() {
                    $(this).dialog("close");
                    $("#valuation").val("").focus();
                    return false;
                });
                event.preventDefault();
                return false;

            }
            $("#coverage").val(parseInt($("#coverage").val().replace(/\D/g,''),10));
            if (!$.isNumeric($("#coverage").val())) {
                ShowError("Dækningsafgiften er ugyldig", function() {
                    $(this).dialog("close");
                    $("#coverage").val("").focus();
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
        $(".deleteButton").click(function(){

            var data = $(this).attr('href').split("#id=");
            ConfirmDialog("Er du sikker på at du vil slette: " + data[0].trim(), data[1].trim())
            return false;
        });
        formatNumber($("#valuation"));
        formatNumber($("#coverage"));
        $("#valuation").on('keyup',function(){
            var n = parseInt($(this).val().replace(/\D/g,''),10);
            $(this).val(n.toLocaleString());
        });
        $("#coverage").on('keyup', function(){
            var n = parseInt($(this).val().replace(/\D/g,''),10);
            $(this).val(n.toLocaleString());
        });
    });
</script>
</body>

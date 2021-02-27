<?php

include_once "WebHelper.php";

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


include_once ("header.php");
?>
<body>
<div>
    <div class="w3-container w3-center">
        <?
        if ($isNew)
            echo "<h1>Opret ny ejendom</h1>";
        else
            echo "<h1>". $property->road . " " . $property->houseNo . "</h1>";

        ?>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center transparentBg">
            <div id="accordion">
                <h3>Stamoplysninger</h3>
                <div>
                    <p>
                    <table>
                        <tr>
                            <th>Adresse:</th>
                            <td><? echo $property->road . " " . $property->houseNo; ?></td>
                        </tr>
                        <tr>
                            <th>By:</th>
                            <td><? echo $property->zip . " " . $property->city; ?></td>
                        </tr>
                        <tr>
                            <th>Din Geo:</th>
                            <td><a href="<? echo $property->dinGeoLink; ?>" target="_blank">Link til Din Geo</a> </td>
                        </tr>
                    </table>

                    </p>
                </div>
                <h3>Skatteoplysninger</h3>
                <div>
                    <p>
                    <table>
                        <tr>
                            <th>Vurdering:</th>
                            <td><? echo number_format((float) $property->valuation, 0 , ",", "."); ?> DKK</td>
                        </tr>
                        <tr>
                            <th>Dækningsafgift:</th>
                            <td><? echo number_format((float) $property->coverage, 0 , ",", "."); ?> DKK</td>
                        </tr>
                    </table>
                    </p>
                </div>
                <h3>Ejer</h3>
                <div>
                    <p>
                        Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
                        purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
                        velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
                        suscipit faucibus urna.
                    </p>
                </div>
                <h3>Anvender</h3>
                <div>
                    <p>
                        Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
                        purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
                        velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
                        suscipit faucibus urna.
                    </p>
                </div>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="actionForm" method="POST">

                <input name="FieldID" ID="FieldID" value="<? echo $property->id; ?>" type="hidden" />
                <input name="Action" ID="Action" value="" type="hidden" />

            </form>
            <h3>Kommentarer:</h3>
            <div id="commentsBox">

            </div>
            <div>
                <textarea class="input-field" type="text" name="comment" id="comment" placeholder="Skriv en kommentar" cols="70" rows="4"></textarea>
                <br/><br/>
                <button id="sendComment">Send kommentar</button>
            </div>
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

    function getAllComments() {
        $.post(
            "listCommentsAjax.php",
            {
                propertyID: "<? echo $property->id; ?>"
            },
            function(data) {
                var data = JSON.parse(data);

                var comments = "";
                var replies = "";
                var item = "";
                var results = new Array();

                var list = $("<ul class='outer-comment'>");
                var item = $("<li>").html(comments);

                for (var i = 0; (i < data.length); i++) {

                    comments = "<div class='comment-row'>"
                        + "<div class='comment-info'><span class='posted-by'>"
                        + data[i]['User']
                        + " </span> <span class='commet-row-label'>den</span> <span class='posted-at'>"
                        + data[i]['Date']
                        + ":</span></div>"
                        + "<div class='comment-text'>"
                        + data[i]['Comment'].replace(/\n/g, "<br />")
                        + "</div>"
                        + "</div>";

                    var item = $("<li>").html(comments);
                    list.append(item);
                }
                $("#commentsBox").html(list);
            });
    }

    $(document).ready(function(){
        $("#name").focus();
        $("#statusRow").delay(2000).fadeOut('slow');
        $("#btnLogout").click(function(){
            $(location).attr('href', 'logout.php')
        });
        $("#btnBack").click(function(){
            $(location).attr('href', 'index.php')
        });
        $("#btnSave").click(function(){

            $("#Action").val("save");
        });
        $("#accordion").accordion();

        getAllComments();

        $("#sendComment").click(function() {
            comment = $("#comment").val();

            $.ajax({
                url : "addCommentAjax.php",
                data : {
                    propertyID: "<? echo $property->id; ?>",
                    comment: comment
                },
                type : 'post',
                success : function(response) {
                    if (response) {
                        $("#comment").val("");
                        getAllComments();
                    } else {
                        alert("Failed to add comments !");
                        return false;
                    }
                }
            });
        });
    });
</script>
</body>

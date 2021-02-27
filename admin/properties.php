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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST["ID"]);
    $action = trim($_POST["Action"]);

    if( $action == "del" and $id != "" ) {

        $errorMsg = Property::deleteProperty($id);
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
    <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <title>Dansk Ejendomsforvaltning - Klagesystem - Administration</title>
</head>
<body>
<div>
    <div class="w3-container w3-center">
        <?
        echo "<h1>Ejendomme i systemet</h1>";

        ?>
    </div>
    <div class="w3-row">
        <div class="w3-col m3 w3-center">
            &nbsp;
        </div>
        <div class="w3-col m6 w3-center transparentBg">
            <p>Klik på en af ejendommene for at redigere</p>
            <table id="propertyTable" class="display dataTable" style="width:100%">
                <thead>
                    <th>Vej</th>
                    <th>Nummer</th>
                    <th>Postnr</th>
                    <th>By</th>
                    <th>Ejendomsvurdering</th>
                    <th>Status</th>
                </thead>
                <tfoot>
                    <th>Vej</th>
                    <th>Nummer</th>
                    <th>Postnr</th>
                    <th>By</th>
                    <th>Ejendomsvurdering</th>
                    <th>Status</th>
                </tfoot>
            </table>
            <ul>
                <li><button id="btnNewProperty">Ny ejendom</button></li>
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
                title: 'Slet ejendom?',
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
        $("#btnNewProperty").click(function(){
            $(location).attr('href', 'editProperty.php?new=1')
        });
        /*$(".deleteButton").click(function(){

            var data = $(this).attr('href').split("#id=");
            ConfirmDialog("Er du sikker på at du vil slette: " + data[0].trim(), data[1].trim())
            return false;
        });*/
        var table = $('#propertyTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'propertyAjax.php'
            },
            'rowId' : "propId",
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.22/i18n/Danish.json"
            },
            'columns': [
                { data: 'road' },
                { data: 'houseNo' },
                { data: 'zip' },
                { data: 'city' },
                { data: 'valuation', render: $.fn.dataTable.render.number( '.', ',', 0, '' ) },
                { data: 'status',
                    render: function(data, type) {
                        if (type === 'display') {
                            var status = '';

                            switch (data) {
                                case "Waiting":
                                    status = 'Afventer';
                                    break;
                                case "In_Progress":
                                    status = 'Under udarbejdelse';
                                    break;
                                case "Canceled":
                                    status = 'Afvist';
                                    break;
                                case "Complete":
                                    status = 'Færdig';
                                    break;
                            }

                            return status;
                        }

                        return data;
                    }
                },
            ]
        });

        $('#propertyTable').on( 'click', 'tr', function () {
            var id = table.row( this ).id();
            if (parseInt(id) > 0)
                $(location).attr('href', 'editProperty.php?id=' + id);

        } );
    });
</script>
</body>

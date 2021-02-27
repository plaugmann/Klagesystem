<?php
 include_once "header.php";
?>
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
        <div class="w3-col m6 w3-center transparentBg">
            <p>Herunder kan du se de ejendomme, som er tilknyttet til dig:</p>
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
            <p>
            <form action="upload.php" method="post" enctype="multipart/form-data" >
                <input id="file" type="file" name="file" />
                <input id="html5-upload-button" type="submit" value="Upload" />
            </form>
            </p>
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
            $(location).attr('href', 'logout.php')
        });
        var table = $('#propertyTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'userPropertyAjax.php'
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
                $(location).attr('href', 'showProperty.php?id=' + id);

        } );
    });
</script>
</body>

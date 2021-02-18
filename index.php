<?php
 include_once "header.php";
?>
<body>
<?php


?>
<h1>Dansk Ejendomsforvaltning - Klagesystem</h1>
<?
echo "<h1>Welcome to ". $_SESSION["user.role"] . ": " . $_SESSION["user.name"] . "</h1>";
?>
<p>
<form action="upload.php" method="post" enctype="multipart/form-data" >
    <input id="file" type="file" name="file" />
    <input id="html5-upload-button" type="submit" value="Upload" />
</form>
</p>
<p><button>Logout</button></p>
<script>
    $(document).ready(function(){
        $("button").click(function(){
            $(location).attr('href', 'logout.php')
        });
    });
</script>
<?


?>
</body>

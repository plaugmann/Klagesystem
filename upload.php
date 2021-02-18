<?php
include_once "header.php";
?>
<body>
<?php

/* Get the name of the file uploaded to Apache */
$filename = $_FILES['file']['name'];

/* Prepare to save the file upload to the upload folder */
$location = "uploads/".$filename;

/* Permanently save the file upload to the upload folder */
if ( move_uploaded_file($_FILES['file']['tmp_name'], $location) ) {
    echo '<p>The HTML5 and php file upload was a success!</p>';
} else {
    echo '<p>The php and HTML5 file upload failed.</p>';
}

?>
<p><button>Go back</button></p>
<script>
    $(document).ready(function(){
        $("button").click(function(){
            $(location).attr('href', 'index.php')
        });
    });
</script>
</body>

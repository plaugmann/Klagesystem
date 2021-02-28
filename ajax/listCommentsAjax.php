<?php
include_once "../WebHelper.php";
$helper = new WebHelper();
$conn = $helper->getConnection();
session_start();

## Read value
$propertyID =  isset($_POST['propertyID']) ? (int) $_POST['propertyID'] : -1;

$sql = "SELECT * FROM Comments Where Property_ID = ". $propertyID ." ORDER BY id desc";

$result = mysqli_query($conn, $sql);
$record_set = array();
while ($row = mysqli_fetch_assoc($result)) {
    array_push($record_set, $row);
}
mysqli_free_result($result);

mysqli_close($conn);
echo json_encode($record_set);
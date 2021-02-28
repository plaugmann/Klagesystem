<?php
include_once "../WebHelper.php";
$helper = new WebHelper();
$conn = $helper->getConnection();
session_start();


## Read value
$comment = isset($_POST['comment']) ? $_POST['comment'] : "";
$propertyID =  isset($_POST['propertyID']) ? (int) $_POST['propertyID'] : -1;
$username = $_SESSION["user.name"];

$sql = "INSERT INTO Comments (Comment, User, Date, Property_ID) Values (?, ?, CURRENT_TIMESTAMP(), ?)";

$result = "Success";
if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "ssi", $param_Comment, $param_User, $param_Property_ID);

    $param_Comment = $comment;
    $param_User = $username;
    $param_Property_ID = $propertyID;

    if (!$stmt->execute()) {
        $result = mysqli_error($conn);
    }
}
$conn->close();

echo $result;
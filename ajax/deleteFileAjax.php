<?php
include_once ("../WebHelper.php");
session_start();

$propertyID = isset($_POST['propertyID']) ? (int)$_POST['propertyID'] : -1;
$fileName = isset($_POST['fileName']) ? $_POST['fileName'] : "";
$uploadDir = '/Klagesystem/uploads/' . $propertyID . "/";

unlink($_SERVER['DOCUMENT_ROOT'] . $uploadDir . $fileName);
Property::addComment($propertyID, $fileName . " blev slettet", $_SESSION["user.name"]);

echo "Succes";


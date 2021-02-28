<?php
/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/
include_once ("../WebHelper.php");
session_start();

// Set the uplaod directory
$uploadDir = '/Klagesystem/uploads/' . $_POST['propertyID'] . "/";

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $uploadDir)) {
    mkdir($_SERVER['DOCUMENT_ROOT'] . $uploadDir, 0777, true);
}


// Set the allowed file extensions
$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'pdf'); // Allowed file extensions

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$uploadDir  = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
	$targetFile = $uploadDir . $_FILES['Filedata']['name'];

	// Validate the filetype
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	if (in_array(strtolower($fileParts['extension']), $fileTypes)) {

		// Save the file
		move_uploaded_file($tempFile, $targetFile);
		Property::addComment($_POST['propertyID'], $_FILES['Filedata']['name'] . " blev tilføjet",$_SESSION["user.name"]);
		echo 1;

	} else {

		// The file type wasn't allowed
		echo 'Invalid file type.';

	}
}
?>
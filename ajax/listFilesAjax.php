<?php

$propertyID =  isset($_POST['propertyID']) ? (int) $_POST['propertyID'] : -1;
$uploadDir = '/Klagesystem/uploads/' . $propertyID . "/";

echo json_encode(dirToArray($_SERVER['DOCUMENT_ROOT'] . $uploadDir));

function dirToArray($dir) {

    $result = array();

    $cdir = scandir($dir);
    foreach ($cdir as $key => $value)
    {
        if (!in_array($value,array(".","..")))
        {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
            {
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
            }
            else
            {
                $result[] = $value;
            }
        }
    }

    return $result;
}

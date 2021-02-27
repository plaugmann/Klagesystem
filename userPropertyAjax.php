<?php
include_once "WebHelper.php";
$helper = new WebHelper();
$conn = $helper->getConnection();
session_start();


## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($conn,$_POST['search']['value']); // Search value
$userID = $_SESSION["user.id"];//$_POST['userID'];

## Search
$searchQuery = " ";
if($searchValue != ''){
    $searchQuery = "and (zip like '%".$searchValue."%' or 
        city like '%".$searchValue."%' or 
        road like '%".$searchValue."%' or 
        valuation like '%".$searchValue."%' or 
        status like'%".$searchValue."%' ) ";
}

## Total number of records without filtering
$sel = mysqli_query($conn,"select count(*) as allcount from Properties WHERE Responsible = ".$userID);
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn,"select count(*) as allcount from Properties WHERE 1 and Responsible =".$userID . " ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$propertyQuery = "select * from Properties WHERE 1 and Responsible =".$userID . " ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$propertyRecords = mysqli_query($conn, $propertyQuery);
$data = array();

while ($row = mysqli_fetch_assoc($propertyRecords)) {
    $data[] = array(
        "propId"=>$row['ID'],
        "road"=>$row['Road'],
        "houseNo"=>$row['HouseNo'],
        "zip"=>$row['Zip'],
        "city"=>$row['City'],
        "valuation"=>$row['Valuation'],
        "status"=>$row['Status']
    );
}

## Response
$response = array(
    "draw" => intval($draw),
    'propertyQuery' => $propertyQuery,
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);

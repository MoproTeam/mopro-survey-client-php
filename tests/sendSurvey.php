<?php

include "../src/ApiClient.php";

include "../src/models/request/survey.inc";

if (!isset($argv[1])) {
    exit("apiKey is missing!");
}

$apiKey = $argv[1];

//Declare Variables to send new survey
$firstName = "Road";
$lastName = "Runner";
$email = "road.runner@yopmail.com";
$phone = "";
$groupName = "eComm";
$groupDesc = "eComm";

//Create an Survey Object
$objSurvey = new Survey($firstName, $lastName, $email, $phone, $groupName, $groupDesc);

$apiClient = new ApiClient($apiKey);

try {
    $result = $apiClient->post("/sendsurvey", $objSurvey);

    //Print Result
    print_r ($result);
}
catch (Exception $e) {
    echo "Caught exception: ",  $e->getMessage(), "\n";
}
?>
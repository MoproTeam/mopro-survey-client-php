<?php

include "../src/ApiClient.php";

include "../src/models/request/survey.inc";

if (!isset($argv[1])) {
    exit("apiKey is missing!");
}

$apiKey = $argv[1];

// Declare Variables to initialize a Survey Model Object
$firstName = "Road";
$lastName = "Runner";
$email = "road.runner@yopmail.com";
$phone = "";
$groupName = "eComm";
$groupDesc = "eComm";

// Create a Survey Model Object
$objSurvey = new Survey($firstName, $lastName, $email, $phone, $groupName, $groupDesc);

// Create an API Client Object
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
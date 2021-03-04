<?php

include "../vendor/autoload.php";

include "../src/models/request/survey.inc";

Logger::configure("../log4php.xml");
$logger = Logger::getLogger('tests/sendSurvey');

if (!isset($argv[1])) {
    $logger->error("apiKey is missing!");
    
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
    $logger->info($result);
}
catch (Exception $e) {
    $logger->error("Caught exception: ",  $e->getMessage());
}
?>
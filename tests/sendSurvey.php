<?php declare(strict_types=1);

include __DIR__ . "/../vendor/autoload.php";
include __DIR__ . "/../src/models/request/survey.inc";

use PHPUnit\Framework\TestCase;

Logger::configure(__DIR__ . "/../log4php.xml");

final class SurveyTest extends TestCase {
    /** @test */
    public function testSendSurvey(): void {
        $apiKey = "Private API key";

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
        $apiClient = new ApiClient($apiKey, false);

        // Post HTTP Request to the API
        $result = false;
        try {
            $result = $apiClient->post("/sendsurvey", null, $objSurvey);

            // Assertion
            $this->assertEquals($result, true);
        }
        catch (Exception $e) {
            echo "Caught exception: ",  $e->getMessage(), "\n";

            // Assertion
            $this->assertEquals($result, false);
        }
    }
}
# Mopro Survey Client in PHP

[![PHP Composer](https://github.com/MoproTeam/mopro-survey-client-php/actions/workflows/php.yml/badge.svg)](https://github.com/MoproTeam/mopro-survey-client-php/actions/workflows/php.yml)

Automatically send surveys to your customers via text message and collect reviews on all the major review sites.

## Usage

> **Note:** This version of the Client requires PHP 5.4 or greater.

1. Send a Survey.
```php
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
```

All other examples are available [here](https://github.com/MoproTeam/mopro-survey-client-php/blob/main/tests).

## License

Please see the [license file](https://github.com/MoproTeam/mopro-survey-client-php/blob/main/LICENSE) for more information.

## Security Vulnerabilities

If you have found a security issue, please contact the maintainers directly at [gaurang.jadia@mopro.com](mailto:gaurang.jadia@mopro.com).
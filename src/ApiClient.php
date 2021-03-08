<?php declare(strict_types=1);

include __DIR__ . "/../vendor/autoload.php";

use GuzzleHttp\Client;

Logger::configure("./log4php.xml");

class ApiClient {
    private $logger;
    private $debug;

    var $apiUrl;
    var $apiKey;
    var $httpClient;

    function __construct($apiKey, $isDebug = false) {
        $this->logger = Logger::getLogger(__CLASS__);
        $this->debug = $isDebug;

        $this->apiKey = $apiKey;

        $this->apiUrl = "https://api.cml.ai/v1/integration";

        //Documentation: http://guzzle3.readthedocs.io/docs.html
        //Documentation: https://github.com/guzzle/guzzle
        $this->httpClient = new Client([
            "debug" => false,           //Set to true to enable debug output with the handler used to send a request. For example, when using cURL to transfer requests, cURL's verbose of CURLOPT_VERBOSE will be emitted.
            "decode_content" => true,   //Specify whether or not Content-Encoding responses (gzip, deflate, etc.) are automatically decoded.
            //"base_uri" => $this->$base_uri,    // Base URI is used with relative requests
            "connect_timeout" => 15,  //Float describing the number of seconds to wait while trying to connect to a server. Use 0 to wait indefinitely (the default behavior).
            "timeout"  => 180,  // You can set any number of default request options. It should only take 180 seconds max!
            "http_errors" => true, //Set to false to disable throwing exceptions on an HTTP protocol errors (i.e., 4xx and 5xx responses).
            "verify" => false,  //Describes the SSL certificate verification behavior of a request.
        ]);
    }

    public function get($urlSuffix, $data) : string {
        return $this->makeRequest("GET", $urlSuffix, $data);
    }

    public function post($urlSuffix, $data) : string {
        return $this->makeRequest("POST", $urlSuffix, $data);
    }

    public function put($urlSuffix, $data) : string {
        return $this->makeRequest("PUT", $urlSuffix, $data);
    }

    public function delete($urlSuffix, $data) : string {
        return $this->makeRequest("DELETE", $urlSuffix, $data);
    }

    private function makeRequest($method, $urlSuffix, $data) : string {
        $url = $this->apiUrl . $urlSuffix;
        
        if ($method == "GET" && !is_null($data)) {
            $url .= "?" . http_build_query($data, '', "&");
        }

        //create a new cURL resource
        $ch = curl_init($url);
        
        //set UserAgent
        curl_setopt($ch, CURLOPT_USERAGENT, "Mopro SDK PHP Client v1.0.0.0");

        //request headers
        $headers = array(
            "Z-Api-Key:" . $this->apiKey
        );

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //don't verify the peer's SSL certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($method == "POST") {
            if ($data != null) {
                //setup request to send json via POST
                $payload = json_encode($data);

                //attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

                //add contentType of headers array
                array_push($headers, "Content-Type:application/json");
            }
        }
        else if ($method == "PUT") {
            if ($data != null) {
                //setup request to send json via POST
                $payload = json_encode($data);

                //attach encoded JSON string to the POST fields
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);    
            }
            
            //add contentType of headers array
            array_push($headers, "Content-Type:application/json");

            //request method = PUT
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        else if ($method == "DELETE") {
            //request method = DELETE
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        //set request headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //execute the request
        $result = curl_exec($ch);

        //to help debug request
        $info = curl_getinfo($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }

        //close cURL resource
        curl_close($ch);

        if (isset($error_msg)) {
            $this->logError($error_msg);

            throw new Exception($error_msg);
        }
        else {
            $response = json_decode($result);
            
            if (!is_null($response) && isset($response) && property_exists($response, "status") && $response->status == 200) {
                $this->logInfo($response);
                
                if (property_exists($response, "data") && !empty((array)$response->data)) {
                    return $response->data;
                }
                else {
                    return true;
                }
            }
            else {
                $this->logError($response);

                if (!is_null($response) && isset($response) && property_exists($response, "error") && property_exists($response->error, "name") && property_exists($response->error, "message")) {
                    throw new Exception($response->error->name . " : " . $response->error->message);
                }
                else if (!is_null($response) && isset($response) && property_exists($response, "error") && property_exists($response->error, "name")) {
                    throw new Exception($response->error->name);
                }
                else if (!is_null($response) && isset($response) && property_exists($response, "error") && property_exists($response->error, "message")) {
                    throw new Exception($response->error->message);
                }
                else {
                    throw new Exception("Non 200 OK Response");
                }
            }
        }
    }

    private function logInfo($data) : void {
        if ($this->debug) {
            $this->logger->info($data);
        }
    }
    
    private function logError($data) : void {
        if ($this->debug) {
            $this->logger->error($data);
        }
    }
}
<?php

/**
 * @file
 *
 * A script for making API calls to retrieve weather information using a CVR number.
 */

// Constants for API URLs
define('CVR_API_URL', 'https://cvrapi.dk/api');
define('VEJR_API_URL', 'https://vejr.eu/api.php');

interface ApiClientInterface
{
    public function fetchData(string $url): string;
}

class CurlApiClient implements ApiClientInterface
{
    public function fetchData(string $url): string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-type: application/json",
                "User-Agent: 'CVR API - test - Amer +45 60811091'"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}

class CvRService
{
    private $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getCityByCvR(string $cvr): string
    {
        $url = CVR_API_URL . '?country=dk&search=' . $cvr;
        $response = $this->apiClient->fetchData($url);
        $result = json_decode($response);

        // Check if the 'city' property exists before accessing it
        return isset($result->city) ? $result->city : '';
    }
}

class WeatherService
{
    private $apiClient;

    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getWeatherByCity(string $city): string
    {
        $url = VEJR_API_URL . '?location=' . $city . '&degree=C';
        return $this->apiClient->fetchData($url);
    }
}

// Usage

$cvr = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_NUMBER_INT);
if (!filter_input(INPUT_GET, 'search', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{8}$/")))) {
    die("Invalid CVR number");
}

$curlClient = new CurlApiClient();
$cvrService = new CvRService($curlClient);
$weatherService = new WeatherService($curlClient);

$city = $cvrService->getCityByCvR($cvr);
$weatherData = $weatherService->getWeatherByCity(urlencode($city));
print_r($weatherData);

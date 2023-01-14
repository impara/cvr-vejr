<?php

/**
 * @file
 *
 * A script for making API calls to retrieve weather information using a CVR number.
 */

// Constants for API URLs
define('CVR_API_URL', 'https://cvrapi.dk/api');
define('VEJR_API_URL', 'https://vejr.eu/api.php');

// Error handling
$error = null;

// Input validation and sanitization
if (!filter_input(INPUT_GET, 'search', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{8}$/")))) {
    die("Invalid CVR number");
}
$cvr = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

// CVR API call using cURL
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => CVR_API_URL . '?country=dk&search=' . $cvr,
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
$err = curl_error($curl);
curl_close($curl);

// Check for errors
if ($err) {
    $error = "CVR API Error: " . $err;
} else {
    // Decode JSON using a library
    $result = json_decode($response);

    // Check for errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "JSON Error: " . json_last_error_msg();
    } else {
        // Extract city and urlencode using a library
        $city = urlencode($result->city);

        // Check for errors
        if (strstr($city, '+')) {
            $city = strstr($city, '+', true);
        }
    }
}

// Check for errors
if ($error) {
    die($error);
}

// Weather API call using cURL
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => VEJR_API_URL . '?location=' . $city . '&degree=C',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Content-type: application/json",
        "User-Agent: 'Weather API - test - Amer +45 60811091'"
    ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Check for errors
if ($err) {
    die("Weather API Error: " . $err);
} else {
    // Print the response
    print_r($response);
}

<?php

/**
 * @file
 */

if (filter_input(INPUT_GET, 'search')) {

    $cvr = filter_input(INPUT_GET, 'search');
    $cvr_api_url = 'https://cvrapi.dk/api?country=dk&search=' . $cvr;

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method' => "GET",
            'header' => "User-Agent: 'CVR API - Kviknet-test - Yusuf Amer +45 60811091'\r\n"
        ),
    );

    $context = stream_context_create($options);
    $cvr_data = file_get_contents($cvr_api_url, FALSE, $context);
    $result = json_decode($cvr_data);
    $city = urlencode($result->city);

    $vejr_api_url = 'https://vejr.eu/api.php?location=' . $city . '&degree=C';
    $vejr_data = file_get_contents($vejr_api_url, FALSE, $context);
    print_r($vejr_data);
} else {
    die("Der er ikke indtastet et CVR-nummer");
}

<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

header('Content-Type: application/json');

$api_url = 'http://127.0.0.1:8000/api/kegiatan';

$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo json_encode(['error' => 'Failed to fetch kegiatan']);
    curl_close($curl);
    exit;
}

curl_close($curl);

echo $response;

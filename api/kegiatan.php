<?php

header('Access-Control-Allow-Origin: *'); // Allow all origins
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// api/kegiatan.php
header('Content-Type: application/json');

// API endpoint
$api_url = 'http://127.0.0.1:8000/api/kegiatan';

// Initialize cURL session
$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute API call
$response = curl_exec($curl);

// Handle errors
if (curl_errno($curl)) {
    echo json_encode(['error' => 'Failed to fetch kegiatan']);
    curl_close($curl);
    exit;
}

// Close cURL session
curl_close($curl);

// Return the API response
echo $response;

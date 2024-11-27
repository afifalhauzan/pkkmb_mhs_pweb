<?php
// Allow Cross-Origin requests (if needed for testing)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Set the response type to JSON
header('Content-Type: application/json');

// Get the NIM from the query parameter
if (!isset($_GET['nim'])) {
    echo json_encode(['error' => 'NIM not found in the request']);
    exit;
}

$nim = $_GET['nim']; // Get NIM from query parameter

// Construct the API URL for fetching user data (Assuming this is the correct endpoint)
$api_url = "http://127.0.0.1:8000/api/mahasiswa/$nim";

// Initialize cURL session
$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute the API request
$response = curl_exec($curl);

// Handle cURL errors
if (curl_errno($curl)) {
    echo json_encode(['error' => 'Failed to fetch data from API', 'details' => curl_error($curl)]);
    curl_close($curl);
    exit;
}

// Check if the API response is empty
if (empty($response)) {
    echo json_encode(['error' => 'Received empty response from API']);
    curl_close($curl);
    exit;
}

// Close cURL session
curl_close($curl);

// Return the API response as JSON (user data)
echo $response;
?>

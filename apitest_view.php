<?php
// Define the API endpoint
$apiUrl = "http://127.0.0.1:8000/api/users";

// Initialize a cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']); // Set header for JSON response

// Execute the cURL request
$response = curl_exec($ch);

// Check if there was an error
if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
    exit;
}

// Close the cURL session
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// Check if the API returned success
if ($data['success']) {
    echo "<h1>Users List</h1>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>NIM</th><th>Name</th><th>Prodi</th><th>Role</th></tr>";

    // Iterate through the users and display their details
    foreach ($data['data'] as $user) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['nim']) . "</td>";
        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
        echo "<td>" . htmlspecialchars($user['prodi']) . "</td>";
        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    // If the API did not return success
    echo "<p>Failed to fetch users. Please try again later.</p>";
}

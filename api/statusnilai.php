<?php

// API URL for fetching task details
$api_status_url = "http://127.0.0.1:8000/api/tugas/status/$nim/$taskId";

$response2 = @file_get_contents($api_status_url);
$status_data = @json_decode($response2, true);

if ($status_data && $status_data['success'] === true) {
    $file_tugas = $status_data['data']['file_tugas'];
    $nilai = $status_data['data']['nilai'];
    $updated_at = $status_data['data']['updated_at'];

    // Prepare JSON response
    $response = [
        'success' => true,
        'nilai_display' => $nilai === null ? "Belum Dinilai" : $nilai,
        'updated_at' => $updated_at,
    ];
} else {
    $response = [
        'success' => false,
        'nilai_display' => "Belum dinilai",
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

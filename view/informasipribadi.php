<?php
// Start the session to retrieve the NIM from the session
session_start();

// Check if the NIM is available in the session
if (!isset($_SESSION['nim'])) {
    echo json_encode(['error' => 'User not logged in or NIM not found']);
    exit;
}

// Get the current user's NIM from the session
$nim = $_SESSION['nim'];

// Construct the API endpoint by passing the nim as a query parameter
$api_url = "http://127.0.0.1:8000/api/mahasiswa/$nim"; // Sending NIM as query parameter

// Initialize cURL session
$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute API call
$response = curl_exec($curl);

// Handle cURL errors
if (curl_errno($curl)) {
    echo json_encode(['error' => 'Failed to fetch data from API', 'details' => curl_error($curl)]);
    curl_close($curl);
    exit;
}

// Check if the API response is empty or invalid
if (empty($response)) {
    echo json_encode(['error' => 'Received empty response from API']);
    curl_close($curl);
    exit;
}

// Decode the JSON response
$userData = json_decode($response, true);

// Close cURL session
curl_close($curl);

// Handle error if the user data is not found
if (isset($userData['error']) || !isset($userData['data'])) {
    echo json_encode(['error' => 'Invalid user data received']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PortalMaba</title>
  <link rel="stylesheet" href="css/informasipribadi.css">
</head>

<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>

    <div class="content">
      <div class="main-content">
        <h3>Informasi Pribadi</h3>
        <form>
          <label>Nama</label>
          <input type="text" value="<?php echo htmlspecialchars($userData['data']['name']); ?>" readonly>

          <label>NIM Kamu</label>
          <input type="text" value="<?php echo htmlspecialchars($userData['data']['nim']); ?>" readonly>

          <label>QC Kamu</label>
          <input type="text" value="<?php echo htmlspecialchars($userData['data']['qc_name'] ?? 'Belum ada QC!'); ?>" readonly>

          <label>Info Cluster</label>
          <input type="text" value="<?php echo htmlspecialchars($userData['data']['cluster_id']); ?>" readonly>
        </form>
      </div>
    </div>
  </div>
  <footer>
    Developed by Guru Genjet Team
  </footer>
</body>

</html>

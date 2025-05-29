<?php
session_start();

if (!isset($_SESSION['nim'])) {
    echo json_encode(['error' => 'User not logged in or NIM not found']);
    exit;
}

$nim = $_SESSION['nim'];

$api_url = "http://127.0.0.1:8000/api/mahasiswa/$nim";

$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo json_encode(['error' => 'Failed to fetch data from API', 'details' => curl_error($curl)]);
    curl_close($curl);
    exit;
}

if (empty($response)) {
    echo json_encode(['error' => 'Received empty response from API']);
    curl_close($curl);
    exit;
}

$userData = json_decode($response, true);

curl_close($curl);

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
    2025
  </footer>
</body>

</html>

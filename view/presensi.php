<?php
// Start the session to retrieve the NIM
session_start();

// Check if the NIM is available in the session
if (!isset($_SESSION['nim'])) {
  echo "User not logged in or NIM not found.";
  exit;
}

// Get the current user's NIM from the session
$nim = $_SESSION['nim'];

// Check if the form was submitted
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the input kode_presensi from the form
  $kode_presensi = $_POST['kode_presensi'] ?? '';

  if (!empty($kode_presensi)) {
    // Construct the API endpoint
    $api_url = "http://127.0.0.1:8000/api/presensi/submit/$nim/$kode_presensi";

    // Initialize cURL session
    $curl = curl_init($api_url);
    curl_setopt($curl, CURLOPT_POST, true); // Set HTTP method to POST
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute API call
    $response = curl_exec($curl);

    // Handle cURL errors
    if (curl_errno($curl)) {
      $message = "Failed to submit presensi. Please try again later. Error: " . curl_error($curl);
    } else {
      // Decode the JSON response
      $response_data = json_decode($response, true);
      if ($response_data) {
        if ($response_data['success']) {
          $message = "Presensi berhasil dikirim!";
        } else {
          $message = $response_data['message'] ?? "Gagal mengirim presensi.";
        }
      } else {
        $message = "Invalid response from the server. Please try again.";
      }
    }

    // Close cURL session
    curl_close($curl);
  } else {
    $message = "Kode presensi tidak boleh kosong.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PortalMaba - Presensi</title>
  <link rel="stylesheet" href="css/presensi.css">
</head>

<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>
    <div class="content">
      <div class="main-content">
        <h3>Masukkan kode presensi</h3>
        <?php if (!empty($message)): ?>
          <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
          <input type="text" name="kode_presensi" placeholder="Masukkan kode presensi" required>
          <button class="buttondefault" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <footer>
    Developed by Guru Genjet Team
  </footer>
</body>

</html>

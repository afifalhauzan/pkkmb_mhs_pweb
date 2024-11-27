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

// Get the selected task ID from the query string
if (!isset($_GET['id'])) {
    echo "No task selected.";
    exit;
}

$taskId = $_GET['id'];

// API URL for fetching task details
$api_url = "http://127.0.0.1:8000/api/mahasiswa/listtugas";

$api_status_url = "http://127.0.0.1:8000/api/tugas/status/$nim/$taskId";

$response2 = @file_get_contents($api_status_url);
$status_data = @json_decode($response2, true);

if ($status_data && $status_data['success'] === true) {
    // If the status is successful, assign the variables
    $file_tugas = $status_data['data']['file_tugas'];
    $nilai = $status_data['data']['nilai'];
    $updated_at = $status_data['data']['updated_at'];

    // Set nilai display
    $nilai_display = $nilai === null ? "Belum Dinilai" : $nilai;
} else {
    // If the success flag is false, don't define the variables
    $file_tugas = null;
    $nilai = null;
    $updated_at = null;
    $nilai_display = "Belum dinilai";
}



// Initialize cURL session to get task details
$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

// Handle cURL errors
if (curl_errno($curl)) {
    echo "Failed to fetch task details. Error: " . curl_error($curl);
    exit;
}

// Decode the JSON response
$taskData = json_decode($response, true);
curl_close($curl);

// Find the task that matches the ID
$selectedTask = null;
if ($taskData && isset($taskData['data'])) {
    foreach ($taskData['data'] as $task) {
        if ($task['ID_Tugas'] == $taskId) {
            $selectedTask = $task;
            break;
        }
    }
}

// Handle the form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input submission link from the form
    $submission_link = $_POST['submission_link'] ?? '';

    // URL encode the submission link to avoid issues
    $submission_link = urlencode($submission_link);

    if (!empty($submission_link)) {
        // Construct the API URL to submit the task
        $submit_api_url = "http://127.0.0.1:8000/api/tugas/submit/$nim/$taskId/$submission_link";

        // Initialize cURL session for task submission
        $curl = curl_init($submit_api_url);
        curl_setopt($curl, CURLOPT_POST, true); // Set HTTP method to POST
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'submission_link' => $submission_link
        ]);

        // Execute the API call
        $submit_response = curl_exec($curl);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PortalMaba - Detail Tugas</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/penugasan.css">
    <link rel="stylesheet" href="css/tugas_detail.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="container">
        <?php include '../includes/sidebar.php'; ?>

        <div class="content">
            <div class="main-content">
                <a href="penugasan.php" class="back-link">Kembali</a>

                <?php if ($selectedTask): ?>
                    <h3><?php echo htmlspecialchars($selectedTask['Judul']); ?></h3>
                    <p><?php echo htmlspecialchars($selectedTask['Deskripsi']); ?></p>

                    <form method="POST" action="">
                        <label for="submission-link">Link Tugasmu :</label>
                        <input type="text" id="submission-link" name="submission_link" placeholder="Masukkan link tugas..." required>
                        <button type="submit">Submit</button>
                    </form>

                    <!-- Message Display -->
                    <?php if (!empty($message)): ?>
                        <p><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>

                    <div>
                        <h3><strong>Nilai :</strong> <?= htmlspecialchars($nilai_display) ?></h3>
                    </div>


                <?php else: ?>
                    <p>Task not found or invalid selection.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        Developed by Guru Genjet Team
    </footer>

    <?php
    // Handle cURL errors during submission
    if (curl_errno($curl)) {
        $message = "Failed to submit task. Please try again later. Error: " . curl_error($curl);
        $alert_type = 'error'; // Set alert type to 'error'
    } else {
        $submit_data = @json_decode($submit_response, true);

        if ($submit_data) {
            if ($submit_data['success']) {
                $message = "Tugas berhasil dikirim, gacor!";
                $alert_type = 'success'; // Success message
            } else {
                $message = $submit_data['message'] ?? "Yah, gagal mengirim tugas.";
                $alert_type = 'error'; // Error message
            }
        } else {
            $message = "Invalid response from the server. Please try again.";
            $alert_type = 'error'; // Error message
        }
    }

    curl_close($curl);

    // Output JavaScript to show SweetAlert
    if (!empty($submit_data)) {
        echo "<script>
            var message = '" . addslashes($message) . "';
            var alertType = '" . $alert_type . "';
            Swal.fire({
                title: alertType === 'success' ? 'Success!' : 'Error!',
                text: message,
                icon: alertType,
                confirmButtonText: 'OK'
            })
        </script>";
    }
    ?>
</body>

</html>
<?php
session_start();

if (!isset($_SESSION['nim'])) {
    echo "User not logged in or NIM not found.";
    exit;
}

$nim = $_SESSION['nim'];

if (!isset($_GET['id'])) {
    echo "No task selected.";
    exit;
}

$taskId = $_GET['id'];

$api_url = "http://127.0.0.1:8000/api/mahasiswa/listtugas";

$api_status_url = "http://127.0.0.1:8000/api/tugas/status/$nim/$taskId";

$response2 = @file_get_contents($api_status_url);
$status_data = @json_decode($response2, true);

if ($status_data && $status_data['success'] === true && isset($status_data['data'])) {
    $file_tugas = $status_data['data']['file_tugas'] ?? null;
    $nilai = $status_data['data']['nilai'] ?? null;
    $updated_at = $status_data['data']['updated_at'] ?? null;
    $text_feedback = $status_data['data']['text_feedback'] ?? null; // Get text_feedback
    $time_submission = $status_data['data']['time_submission'] ?? null; // Get time_submission

    $nilai_display = $nilai === null ? "Belum Dinilai" : htmlspecialchars($nilai);
    $feedback_display = $text_feedback === null ? "Belum Ada Feedback" : htmlspecialchars($text_feedback);

    if ($time_submission !== null) {
        // Format time_submission if it exists
        $submission_time_display = date('d M Y, H:i', strtotime($time_submission));
    } else {
        $submission_time_display = "Belum Ada Pengumpulan";
    }

} else {
    $file_tugas = null;
    $nilai_display = "Belum dinilai";
    $feedback_display = "Belum Ada Feedback";
    $submission_time_display = "Belum Ada Pengumpulan";
}


$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo "Failed to fetch task details. Error: " . curl_error($curl);
    exit;
}

$taskData = json_decode($response, true);
curl_close($curl);

$selectedTask = null;
if ($taskData && isset($taskData['data'])) {
    foreach ($taskData['data'] as $task) {
        if ($task['ID_Tugas'] == $taskId) {
            $selectedTask = $task;
            break;
        }
    }
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submission_link = $_POST['submission_link'] ?? '';

    $submission_link = urlencode($submission_link);

    if (!empty($submission_link)) {
        $submit_api_url = "http://127.0.0.1:8000/api/tugas/submit/$nim/$taskId/$submission_link";

        $curl = curl_init($submit_api_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'submission_link' => $submission_link
        ]);

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
                        <h3><strong>Nilai :</strong> <?= $nilai_display ?></h3>
                        <?php if ($text_feedback !== null): ?>
                            <p><strong>Feedback:</strong> <?= $feedback_display ?></p>
                        <?php endif; ?>
                        <p><strong>Waktu Pengumpulan:</strong> <?= $submission_time_display ?></p>
                        <?php if ($file_tugas !== null): ?>
                            <p><strong>Link Tugas yang Dikumpulkan:</strong> <a href="<?= htmlspecialchars($file_tugas) ?>" target="_blank"><?= htmlspecialchars($file_tugas) ?></a></p>
                        <?php endif; ?>
                    </div>


                <?php else: ?>
                    <p>Task not found or invalid selection.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        2025
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
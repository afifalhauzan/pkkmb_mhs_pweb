<?php
session_start();

if (!isset($_SESSION['nim'])) {
    echo "User not logged in or NIM not found.";
    exit;
}

$nim = $_SESSION['nim'];

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_presensi = $_POST['kode_presensi'] ?? '';

    if (!empty($kode_presensi)) {
        // Encode kode_presensi for URL path if your API expects it this way
        // If your API expects it as a form field, adjust the curl_setopt and Laravel controller
        $encoded_kode_presensi = urlencode($kode_presensi);
        $api_url_submit = "http://127.0.0.1:8000/api/presensi/submit/$nim/$encoded_kode_presensi";

        $curl_submit = curl_init($api_url_submit);
        curl_setopt($curl_submit, CURLOPT_POST, true);
        curl_setopt($curl_submit, CURLOPT_RETURNTRANSFER, true);

        $response_submit = curl_exec($curl_submit);

        if (curl_errno($curl_submit)) {
            $message = "Failed to submit presensi. Please try again later. Error: " . curl_error($curl_submit);
        } else {
            $response_data = json_decode($response_submit, true);
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
        curl_close($curl_submit);
    } else {
        $message = "Kode presensi tidak boleh kosong.";
    }
    // After submission, it's often good to refresh the page to show updated attendance
    // header("Location: presensi.php");
    // exit;
}


// --- Fetch Attendance Rekap Data ---
$rekap_api_url = "http://127.0.0.1:8000/api/mahasiswa/rekap-kehadiran/$nim";

$rekap_response = @file_get_contents($rekap_api_url);
$rekap_data = @json_decode($rekap_response, true);

$attended_activities = [];
$total_attended_count = 0;
$fetch_error = '';

if ($rekap_data && $rekap_data['success'] === true && isset($rekap_data['data'])) {
    $attended_activities = $rekap_data['data'];
    $total_attended_count = $rekap_data['summary']['total_attended_activities'] ?? 0;
} else {
    $fetch_error = $rekap_data['message'] ?? "Failed to fetch attendance recapitulation.";
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
                    <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="text" name="kode_presensi" placeholder="Masukkan kode presensi" required>
                    <button class="buttondefault" type="submit">Submit</button>
                </form>

                <hr style="margin-top: 20px; margin-bottom: 20px;">

                <h3>Rekap Kehadiran Anda</h3>

                <?php if (!empty($fetch_error)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($fetch_error); ?></p>
                <?php elseif (empty($attended_activities)): ?>
                    <p>Anda belum memiliki riwayat kehadiran.</p>
                <?php else: ?>
                    <p>Total Kegiatan yang Dihadiri: **<?php echo $total_attended_count; ?>**</p>
                    <table border="1" style="width:100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; text-align: left; background-color: #f2f2f2;">Nama Kegiatan</th>
                                <th style="padding: 8px; text-align: left; background-color: #f2f2f2;">Waktu Presensi</th>
                                <th style="padding: 8px; text-align: left; background-color: #f2f2f2;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attended_activities as $activity): ?>
                                <tr>
                                    <td style="padding: 8px;"><?php echo htmlspecialchars($activity['kegiatan_nama']); ?></td>
                                    <td style="padding: 8px;"><?php echo htmlspecialchars($activity['waktu_presensi']); ?></td>
                                    <td style="padding: 8px;"><?php echo htmlspecialchars($activity['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <footer>
        2025
    </footer>
</body>

</html>
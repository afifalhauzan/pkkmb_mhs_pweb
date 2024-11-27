<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PortalMaba - Penugasan</title>
  <link rel="stylesheet" href="css/penugasan.css">
</head>

<body>
  <div class="container">
    <?php include '../includes/sidebar.php'; ?>

    <div class="content">
      <div class="main-content">
        <h3>Daftar Tugas</h3>
        <div class="task-list">
          <?php
          // API endpoint
          $api_url = "http://127.0.0.1:8000/api/mahasiswa/listtugas";

          // Initialize cURL session
          $curl = curl_init($api_url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

          // Execute API call
          $response = curl_exec($curl);

          // Handle cURL errors
          if (curl_errno($curl)) {
            echo "<p>Failed to fetch tasks. Please try again later.</p>";
            curl_close($curl);
            exit;
          }

          // Decode the JSON response
          $taskData = json_decode($response, true);

          // Close cURL session
          curl_close($curl);

          // Check if the response is valid
          if ($taskData && $taskData['success']) {
            foreach ($taskData['data'] as $task) {
              // Use ID_Tugas as the identifier in the URL
              echo '<div class="task-item">';
              echo '<h4><a href="tugas_detail.php?id=' . urlencode($task['ID_Tugas']) . '">' . htmlspecialchars($task['Judul']) . '</a></h4>';
              echo '</div>';
            }
          } else {
            echo "<p>No tasks found or failed to fetch tasks.</p>";
          }

          ?>
        </div>
      </div>
    </div>
  </div>
  <footer>
    Developed by Guru Genjet Team
  </footer>
</body>

</html>

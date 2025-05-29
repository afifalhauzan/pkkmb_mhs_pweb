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
          $api_url = "http://127.0.0.1:8000/api/mahasiswa/listtugas";

          $curl = curl_init($api_url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

          $response = curl_exec($curl);

          if (curl_errno($curl)) {
            echo "<p>Failed to fetch tasks. Please try again later.</p>";
            curl_close($curl);
            exit;
          }

          $taskData = json_decode($response, true);

          curl_close($curl);

          if ($taskData && $taskData['success']) {
            foreach ($taskData['data'] as $task) {
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
    2025
  </footer>
</body>

</html>

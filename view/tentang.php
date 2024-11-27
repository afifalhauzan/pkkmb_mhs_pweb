<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Maba</title>
  <link rel="stylesheet" href="css/tentang.css">
</head>

<body>
  <div class="wrapper">
    <?php include '../includes/navbar.php'; ?>

    <div class="main-content">
      <h1>Segala informasi PKKMB, disini!</h1>
      <p>Hai adek-adek maba!</p>
      <div class="news-section">
        <h2>Daftar Kegiatan</h2>
        <div id="news-container" class="news-container">
          <!-- Cards will be inserted here dynamically -->
        </div>
      </div>
    </div>
  </div>

  <footer>
    Developed by Guru Genjet Team
  </footer>

  <script>
    // Fetch kegiatan data
    fetch('../api/kegiatan.php')
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById('news-container');

        // Check if the API response indicates success
        if (!data.success) {
          container.innerHTML = `<p>Failed to load kegiatan: ${data.message || 'Unknown error'}</p>`;
          return;
        }

        // Populate the news container with kegiatan data
        const kegiatanData = data.data; // Access the `data` array
        container.innerHTML = kegiatanData.map(kegiatan => `
      <div class="news-card">
        <h3>${kegiatan.Nama}</h3>
        <p>Tahun: ${kegiatan.Tahun}</p>
      </div>
    `).join('');
      })
      .catch(error => {
        console.error('Error fetching kegiatan:', error);
        document.getElementById('news-container').innerHTML = '<p>Failed to load kegiatan</p>';
      });
  </script>
</body>

</html>
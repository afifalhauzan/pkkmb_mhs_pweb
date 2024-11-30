<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="wrapper">
 
  <?php include '../includes/navbar.php'; ?>
    <div class="login-container">
      <div class="login-box">
        <h2>Login</h2>
        <form action="../proses_login.php" method="POST">
          <input type="text" name="nim" placeholder="NIM" required>
          <input type="password" name="password" placeholder="password" required>
          <button type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>


  <footer>
    Developed by Guru Genjet Team
  </footer>
</body>
</html>

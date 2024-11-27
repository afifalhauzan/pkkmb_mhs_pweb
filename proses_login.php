<?php
include 'includes/koneksi.php'; // Include your database connection file

session_start(); // Start session to store user data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    // Prepare and execute query to get the hashed password and user data
    $stmt = $conn->prepare('SELECT NIM, Nama, Password FROM mahasiswa WHERE NIM = ?');
    $stmt->bind_param('s', $nim);
    $stmt->execute();
    $stmt->bind_result($dbNim, $nama, $dbpassword);
    $stmt->fetch();
    $stmt->close();

    if ($password === $dbpassword) {
        // Password matches, login successful
        $_SESSION['nim'] = $dbNim;
        $_SESSION['nama'] = $nama;

        // Redirect to a protected page
        header('Location: view/informasipribadi.php');
        exit();
    } else {
        // Invalid credentials
        echo '<p>Invalid NIM or password. Please try again.</p>';
    }
}
?>

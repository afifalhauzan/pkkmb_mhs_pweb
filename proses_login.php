<?php
include 'includes/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT NIM, Nama, Password FROM mahasiswa WHERE NIM = ?');
    $stmt->bind_param('s', $nim);
    $stmt->execute();
    $stmt->bind_result($dbNim, $nama, $dbpassword);
    $stmt->fetch();
    $stmt->close();

    if ($password === $dbpassword) {
        $_SESSION['nim'] = $dbNim;
        $_SESSION['nama'] = $nama;

        header('Location: view/informasipribadi.php');
        exit();
    } else {
        echo '<p>Invalid NIM or password. Please try again.</p>';
    }
}
?>

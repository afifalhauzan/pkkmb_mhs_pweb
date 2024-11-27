<?php
$host = '192.168.56.101';
$username = 'dbdpti'; // Change if your MySQL username is different
$password = 'rahasia'; // Change if you have a password set for MySQL
$database = 'pkkmb_mahasiswa'; // Replace with your actual database name

// Create a MySQL connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>


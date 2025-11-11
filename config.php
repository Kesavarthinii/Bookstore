<?php
$servername = "127.0.0.1"; // or "localhost"
$username   = "root";
$password   = ""; // default is empty in XAMPP
$dbname     = "bookstore_db";
$port       = 3307; // ✅ important: set to 3307

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
$host = "localhost";
$user = "root";          // default XAMPP user
$password = "";          // default XAMPP password
$database = "portfolio_db";  // ✅ use your database name

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
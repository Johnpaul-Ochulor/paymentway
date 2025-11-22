<?php
$host = "localhost";
$user = "root";      // default XAMPP username
$pass = "";          // default XAMPP password (empty)
$dbname = "product_store";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}
?>

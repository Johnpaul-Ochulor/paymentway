<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
include '../db.php';

$id = $_GET['id'] ?? 0;
$res = $conn->query("SELECT image FROM products WHERE id=$id");
if ($res->num_rows > 0) {
  $img = $res->fetch_assoc()['image'];
  @unlink("../uploads/" . $img);
  $conn->query("DELETE FROM products WHERE id=$id");
}
header("Location: ../index.php");
exit;
?>

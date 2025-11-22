<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
include '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h3>Add New Product</h3>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Name:</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Price (₦):</label>
        <input type="number" name="price" class="form-control" step="0.01" required>
      </div>
      <div class="mb-3">
        <label>Image:</label>
        <input type="file" name="image" class="form-control" required>
      </div>
      <button type="submit" name="submit" class="btn btn-success">Add Product</button>
      <a href="../index.php" class="btn btn-secondary">Back</a>
    </form>

<?php
if (isset($_POST['submit'])) {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $img = $_FILES['image'];

  if ($img['error'] == 0) {
    $ext = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];
    if (!in_array($ext, $allowed)) {
      echo "<div class='alert alert-danger mt-3'>Invalid file type.</div>";
    } elseif ($img['size'] > 2 * 1024 * 1024) {
      echo "<div class='alert alert-danger mt-3'>Image too large (max 2MB).</div>";
    } else {
      $newName = uniqid() . "." . $ext;
      $target = "../uploads/" . $newName;
      list($w, $h) = getimagesize($img['tmp_name']);
      $nw = 600; $nh = ($h / $w) * $nw;
      $src = ($ext == 'png') ? imagecreateformpng($img['tmp_name']) : imagecreateformjpeg($img['tmp_name']);
      $dst = imagecreatetruecolor($nw, $nh);
      imagecopyresampled($dst, $src, 0,0,0,0, $nw,$nh,$w,$h);
      ($ext == 'png') ? imagepng($dst, $target, 6) : imagejpeg($dst, $target, 75);
      imagedestroy($src); imagedestroy($dst);

      $conn->query("INSERT INTO products (name, price, image) VALUES ('$name','$price','$newName')");
      echo "<div class='alert alert-success mt-3'>Product added successfully!</div>";
    }
    
  }
}
?>
  </div>
</body>
</html>

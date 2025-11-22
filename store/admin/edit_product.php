<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
include '../db.php';

$id = $_GET['id'] ?? 0;
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $res->fetch_assoc();
if (!$product) die("Product not found!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h3>Edit Product</h3>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Name:</label>
        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
      </div>
      <div class="mb-3">
        <label>Price (₦):</label>
        <input type="number" name="price" class="form-control" step="0.01" value="<?php echo $product['price']; ?>" required>
      </div>
      <div class="mb-3">
        <label>Current Image:</label><br>
        <img src="../uploads/<?php echo $product['image']; ?>" width="150" class="rounded mb-2"><br>
        <input type="file" name="image" class="form-control">
      </div>
      <button name="update" class="btn btn-success">Update</button>
      <a href="../index.php" class="btn btn-secondary">Cancel</a>
    </form>

<?php
if (isset($_POST['update'])) {
  $name = trim($_POST['name']);
  $price = floatval($_POST['price']);

  if (!empty($_FILES['image']['name'])) {
    $img = $_FILES['image'];
    $ext = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png'];
    if (in_array($ext,$allowed)) {
      $newName = uniqid() . "." . $ext;
      $target = "../uploads/" . $newName;
      list($w,$h) = getimagesize($img['tmp_name']);
      $nw = 600; $nh = ($h/$w)*$nw;
      $src = ($ext=='png')?imagecreatefrompng($img['tmp_name']):imagecreatefromjpeg($img['tmp_name']);
      $dst = imagecreatetruecolor($nw,$nh);
      imagecopyresampled($dst,$src,0,0,0,0,$nw,$nh,$w,$h);
      ($ext=='png')?imagepng($dst,$target,6):imagejpeg($dst,$target,75);
      imagedestroy($src); imagedestroy($dst);
      unlink("../uploads/".$product['image']);
      $conn->query("UPDATE products SET name='$name', price='$price', image='$newName' WHERE id=$id");
    }
  } else {
    $conn->query("UPDATE products SET name='$name', price='$price' WHERE id=$id");
  }

  echo "<div class='alert alert-success mt-3'>Product updated!</div>";
  echo "<meta http-equiv='refresh' content='1;url=../index.php'>";
}
?>
  </div>
</body>
</html>

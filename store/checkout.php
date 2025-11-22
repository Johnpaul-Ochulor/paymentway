<?php
include 'db.php';
$id = $_GET['id'] ?? 0;
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();
if (!$product) {
  die("<div style='padding:40px;text-align:center;'>Product not found!</div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - <?php echo htmlspecialchars($product['name']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .checkout-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
      max-width: 600px;
      margin: 50px auto;
    }
    .product-img {
      max-width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="checkout-card">
    <h3 class="mb-4 text-center">Checkout</h3>
    <div class="text-center mb-4">
      <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="" class="product-img mb-3">
      <h5><?php echo htmlspecialchars($product['name']); ?></h5>
      <h4 class="text-success">₦<?php echo number_format($product['price'], 0); ?></h4>
    </div>

    <form method="post" action="">
      <div class="mb-3">
        <label>Full Name:</label>
        <input type="text" name="fullname" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Phone:</label>
        <input type="text" name="phone" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Address:</label>
        <textarea name="address" class="form-control" rows="3" required></textarea>
      </div>
      <button type="submit" name="place_order" class="btn btn-success w-100">Place Order</button>
      <a href="index.php" class="btn btn-secondary w-100 mt-2">Back to Store</a>
    </form>

    <?php
    if (isset($_POST['place_order'])) {
      $name = htmlspecialchars($_POST['fullname']);
      $email = htmlspecialchars($_POST['email']);
      $phone = htmlspecialchars($_POST['phone']);
      $address = htmlspecialchars($_POST['address']);
      echo "<div class='alert alert-success mt-4 text-center'>
              Thank you, <b>$name</b>!<br>
              Your order for <b>{$product['name']}</b> has been received.<br>
              We'll contact you soon at <b>$email</b>.
            </div>";
    }
    ?>
  </div>
</body>
</html>

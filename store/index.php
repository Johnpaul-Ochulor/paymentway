<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .product-card {
      border: 1px solid #ddd; border-radius: 12px; padding: 15px;
      text-align: center; background: #fff; transition: 0.3s; height: 100%;
    }
    .product-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); transform: translateY(-3px); }
    .product-card img { max-width: 100%; height: 180px; object-fit: cover; border-radius: 8px; }
  </style>
</head>
<body>
  <div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Sponsored Products</h2>
      <?php if (isset($_SESSION['admin'])): ?>
        <div>
          <a href="admin/add_product.php" class="btn btn-primary me-2">Add Product</a>
          <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-primary">Admin Login</a>
      <?php endif; ?>
    </div>

    <!-- Search & Filter -->
    <form method="get" class="row g-3 mb-4">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name..."
               value="<?php echo $_GET['search'] ?? ''; ?>">
      </div>
      <div class="col-md-2">
        <input type="number" name="min_price" class="form-control" placeholder="Min ₦"
               value="<?php echo $_GET['min_price'] ?? ''; ?>">
      </div>
      <div class="col-md-2">
        <input type="number" name="max_price" class="form-control" placeholder="Max ₦"
               value="<?php echo $_GET['max_price'] ?? ''; ?>">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100">Filter</button>
      </div>
      <div class="col-md-2">
        <a href="index.php" class="btn btn-secondary w-100">Reset</a>
      </div>
    </form>

    <!-- Product Grid -->
    <div class="row g-4">
      <?php
      $query = "SELECT * FROM products WHERE 1";
      if (!empty($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $query .= " AND name LIKE '%$search%'";
      }
      if (!empty($_GET['min_price'])) $query .= " AND price >= " . floatval($_GET['min_price']);
      if (!empty($_GET['max_price'])) $query .= " AND price <= " . floatval($_GET['max_price']);
      $query .= " ORDER BY id DESC";

      $result = $conn->query($query);
      if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
        <div class="col-md-3 col-sm-6">
          <div class="product-card d-flex flex-column justify-content-between">
            <div>
              <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="">
              <h6 class="mt-3"><?php echo htmlspecialchars($row['name']); ?></h6>
              <p class="text-success fw-bold">₦<?php echo number_format($row['price'], 0); ?></p>
            </div>
           <?php if (isset($_SESSION['admin'])): ?>
  <div class="mt-3">
    <a href="admin/edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
    <a href="admin/delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
  </div>
<?php else: ?>
  <div class="mt-3">
    <a href="checkout.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary w-100">Buy Now</a>
  </div>
<?php endif; ?>

          </div>
        </div>
      <?php endwhile; else: ?>
        <div class="text-center text-muted py-5">No products found.</div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

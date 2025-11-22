<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
include '../db.php';

// Fetch dashboard data
$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$latest = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .card {
      border-radius: 12px; 
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .product-thumb {
      width: 60px; height: 60px; object-fit: cover; border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Admin Dashboard</h2>
      <div>
        <a href="../index.php" class="btn btn-secondary me-2">View Store</a>
        <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-center p-4">
          <h4 class="fw-bold">Total Products</h4>
          <h2 class="text-success fw-bold"><?php echo $totalProducts; ?></h2>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center p-4">
          <h4 class="fw-bold">Add New Product</h4>
          <a href="add_product.php" class="btn btn-success mt-3">Add Product</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center p-4">
          <h4 class="fw-bold">Manage Products</h4>
          <a href="../index.php" class="btn btn-primary mt-3">Go to Store</a>
        </div>
      </div>
    </div>

    <!-- Recent Products -->
    <div class="card p-4">
      <h4 class="mb-3">Recently Added Products</h4>
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price (₦)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $latest->fetch_assoc()): ?>
          <tr>
            <td><img src="../uploads/<?php echo $row['image']; ?>" class="product-thumb"></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>₦<?php echo number_format($row['price'], 0); ?></td>
            <td>
              <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

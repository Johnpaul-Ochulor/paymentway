<?php
session_start();
$admin_user = "admin";
$admin_pass = "12345";

if (isset($_POST['login'])) {
  if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
    $_SESSION['admin'] = true;
    header("Location: index.php");
    exit;
  } else {
    $error = "Invalid username or password!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height:100vh;">
  <div class="card shadow p-4" style="width:350px;">
    <h4 class="text-center mb-4">Admin Login</h4>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button name="login" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>

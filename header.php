<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($page_title ?? 'EduPress Store') ?></title>
  <link rel="stylesheet" href="style.css">
  <script defer src="main.js"></script>
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1 class="logo"><a href="index.php">EduPress</a></h1>
      <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="print.php">Print Documents</a>
        <a href="cart.php">Cart (<span id="cart-count">0</span>)</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if (($_SESSION['user_email'] ?? '') === 'admin@edupress.lk'): ?>
            <a href="admin.php">Admin Panel</a>
          <?php endif; ?>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="container">

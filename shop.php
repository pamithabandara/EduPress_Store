<?php
// shop.php
$page_title = 'Shop – EduPress Store';
include 'header.php';

// 1) Connect to the database
$conn = mysqli_connect('localhost','root','', 'edupress_db');
if (!$conn) {
  die('Database connection error: ' . mysqli_connect_error());
}
$search = trim($_GET['search'] ?? '');

// 2) Fetch all products
if ($search !== '') {
  $safe = mysqli_real_escape_string($conn, $search);
  $sql = "SELECT * FROM products WHERE name LIKE '%$safe%'";
} else {
  $sql = "SELECT * FROM products";
}

$result = mysqli_query($conn, $sql);
?>
<?php
$search = trim($_GET['search'] ?? '');
?>
<form method="get" class="search-form">
  <input type="text" name="search"
         placeholder="Search products..."
         value="<?= htmlspecialchars($search) ?>">
  <button type="submit">Search</button>
</form>

<section class="shop">
  <h2>Our Products</h2>
  <div class="product-grid">
    <?php while ($product = mysqli_fetch_assoc($result)): ?>
      <div class="product-card">
        <img src="images/<?= htmlspecialchars($product['image']) ?>"
             alt="<?= htmlspecialchars($product['name']) ?>">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p class="price">Rs. <?= number_format($product['price'], 2) ?></p>
        <button onclick="addToCart(<?= $product['id'] ?>)">
          Add to Cart
        </button>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<?php
mysqli_close($conn);
include 'footer.php';
?>

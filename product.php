<?php
// product.php
$page_title = 'Product Details – EduPress Store';
include 'header.php';

// 1) Get & validate product ID
$prod_id = intval($_GET['id'] ?? 0);
if ($prod_id <= 0) {
  echo "<p>Invalid product.</p>";
  include 'footer.php';
  exit;
}

// 2) Connect to DB
$conn = mysqli_connect('localhost','root','', 'edupress_db');
if (!$conn) {
  die('DB error: '.mysqli_connect_error());
}

// 3) Fetch product
$res = mysqli_query($conn,
  "SELECT * FROM products WHERE id = $prod_id"
);
if (mysqli_num_rows($res) === 0) {
  echo "<p>Product not found.</p>";
  include 'footer.php';
  exit;
}
$product = mysqli_fetch_assoc($res);
?>

<section class="product-detail">
  <div class="detail-image">
    <img src="images/<?= htmlspecialchars($product['image']) ?>"
         alt="<?= htmlspecialchars($product['name']) ?>">
  </div>
  <div class="detail-info">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <p class="price">Rs. <?= number_format($product['price'],2) ?></p>
    <button onclick="addToCart(<?= $product['id'] ?>)">
      Add to Cart
    </button>
    <p><a href="shop.php">&larr; Back to Shop</a></p>
  </div>
</section>

<?php
mysqli_close($conn);
include 'footer.php';
?>

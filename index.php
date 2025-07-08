<?php
  // index.php
  $page_title = 'Home – EduPress Store';
  include 'header.php';
?>

<!-- Hero Banner -->
<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-content container">
    <h1>Your One-Stop Study & Print Shop</h1>
    <p>Top-quality stationery, notebooks, printer supplies & custom print jobs—delivered fast.</p>
    <a href="shop.php" class="btn btn-primary">Shop Now</a>
  </div>
</section>

<!-- Shop by Category -->
<section class="categories container">
  <h2>Our Product and Service Categories</h2>
  <div class="category-grid">
    <a href="shop.php?cat=notebooks" class="category-card">
      <img src="images/icon-notebook.jpg" alt="Notebooks">
      <span>Notebooks</span>
    </a>
    <a href="shop.php?cat=pens" class="category-card">
      <img src="images/icon-pen.jpg" alt="Pens & Markers">
      <span>Pens & Markers</span>
    </a>
    <a href="shop.php?cat=paper" class="category-card">
      <img src="images/icon-paper.jpg" alt="Printing Paper">
      <span>Printing Paper</span>
    </a>
    <a href="print.php?cat=print" class="category-card">
      <img src="images/icon-print.jpg" alt="Print Jobs">
      <span>Print Jobs</span>
    </a>
        <a href="shop.php?cat=print" class="category-card">
      <img src="images/icon-art.jpg" alt="Art Supplies">
      <span>Art Supplies</span>
    </a>
        <a href="shop.php?cat=print" class="category-card">
      <img src="images/icon-ink.jpg" alt="Ink & Toner">
      <span>Ink & Toner</span>
    </a>
        <a href="shop.php?cat=print" class="category-card">
      <img src="images/icon-desk.jpg" alt="Desk Accessories">
      <span>Desk Accessories</span>
    </a>
  </div>
</section>


<!-- Featured Products -->
<section class="featured container">
  <h2>Featured Products</h2>
  <div class="product-grid">
    <?php
      $conn = mysqli_connect('localhost','root','','edupress_db');
      $res  = mysqli_query($conn, "SELECT * FROM products LIMIT 4");
      while($p = mysqli_fetch_assoc($res)):
    ?>
      <div class="product-card">
        <img src="images/<?=htmlspecialchars($p['image'])?>" alt="<?=htmlspecialchars($p['name'])?>">
        <div class="product-info">
          <h3><?=htmlspecialchars($p['name'])?></h3>
          <div class="price">Rs. <?=number_format($p['price'],2)?></div>
          <button onclick="addToCart(<?=$p['id']?>)">Add to Cart</button>
        </div>
      </div>
    <?php endwhile; mysqli_close($conn); ?>
  </div>
</section>

<!-- Promotional Banners -->
<section class="promo container">
  <div class="promo-card" style="background:url('images/promo-1.jpg') center/cover;">
    <div class="promo-content">
      <h3>Back to School Sale</h3>
      <a href="shop.php?cat=notebooks" class="btn btn-secondary">Explore Now</a>
    </div>
  </div>
  <div class="promo-card" style="background:url('images/promo-2.jpg') center/cover;">
    <div class="promo-content">
      <h3>Custom Print Deals</h3>
      <a href="print.php" class="btn btn-secondary">Upload & Print</a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>

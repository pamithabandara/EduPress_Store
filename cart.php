<?php
// cart.php
$page_title = 'Cart – EduPress Store';
include 'header.php';
?>

<section class="cart">
  <h2>Your Cart</h2>

  <?php
  $hasPrintJobs = isset($_SESSION['print_cart']) && count($_SESSION['print_cart']);
  ?>

  <?php if ($hasPrintJobs): ?>
    <h3>Print Jobs</h3>
    <table>
      <thead>
        <tr>
          <th>File</th><th>Options</th><th>Pages</th><th>Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['print_cart'] as $pj): ?>
          <tr>
            <td><?= htmlspecialchars($pj['orig_name']) ?></td>
            <td><?= htmlspecialchars($pj['options']) ?></td>
            <td><?= $pj['pages'] ?></td>
            <td>Rs. <?= number_format($pj['price'],2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <h3>Stationery Products</h3>
  <table>
    <thead>
      <tr>
        <th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th>
      </tr>
    </thead>
    <tbody id="cart-items"></tbody>
    <tfoot>
      <tr>
        <td colspan="3"><strong>Total </strong></td>
        <td id="cart-total">Rs. 0.00</td>
      </tr>
    </tfoot>
  </table>

  <button id="checkout-btn" onclick="goToCheckout()">
    Checkout
  </button>
</section>


<?php include 'footer.php'; ?>

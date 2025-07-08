<?php
session_start();
$page_title = 'Payment – EduPress Store';
include 'header.php';

// Get order ID
$order_id = intval($_GET['order_id'] ?? 0);
if ($order_id <= 0) {
  echo "<p>Invalid order.</p>";
  include 'footer.php';
  exit;
}
?>

<section class="payment-gateway">
  <div class="payment-card">
    <h2>Secure Payment</h2>
    <p>Order #<?= $order_id ?> – Enter your card details:</p>
    <form method="post" action="thanks.php">
      <input type="hidden" name="order_id" value="<?= $order_id ?>">

      <div class="field">
        <label>Card Number</label>
        <input type="text" name="card" maxlength="19" required placeholder="4111 1111 1111 1111">
      </div>

      <div class="field-row">
        <div class="field">
          <label>Expiry Date</label>
          <input type="text" name="expiry" required placeholder="MM/YY">
        </div>
        <div class="field">
          <label>CVV</label>
          <input type="text" name="cvv" maxlength="4" required placeholder="123">
        </div>
      </div>

      <button type="submit">💳 Pay Now</button>
    </form>
  </div>
</section>


<?php include 'footer.php'; ?>

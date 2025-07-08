<?php
// checkout.php
$page_title = 'Checkout – EduPress Store';
include 'header.php';

// 1) Connect to the database
$conn = mysqli_connect('localhost','root','', 'edupress_db');
if (!$conn) {
  die('DB error: '.mysqli_connect_error());
}

// 2) Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize inputs
  $email   = mysqli_real_escape_string($conn, $_POST['email']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $cart    = json_decode($_POST['cart'], true);

  // Find or create user
  $res = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
  if (mysqli_num_rows($res)) {
    $user = mysqli_fetch_assoc($res);
    $user_id = $user['id'];
  } else {
    mysqli_query($conn, "INSERT INTO users(email, pass_hash) VALUES('$email','')");
    $user_id = mysqli_insert_id($conn);
  }

    // Also calculate total for print jobs
  $printTotal = 0;
  if (isset($_SESSION['print_cart'])) {
    foreach ($_SESSION['print_cart'] as $pj) {
      $printTotal += $pj['price'];
    }
  }

  // Calculate total for products
  $total = 0;
  foreach ($cart as $pid => $qty) {
    $p = mysqli_fetch_assoc(
      mysqli_query($conn, "SELECT price FROM products WHERE id=$pid")
    );
    $total += $p['price'] * $qty;
  }

  $grandTotal = $total + $printTotal;

  // Create order
  mysqli_query($conn,
    "INSERT INTO orders(user_id, total) VALUES($user_id, $grandTotal)"
  );
  $order_id = mysqli_insert_id($conn);

  // Insert products
  foreach ($cart as $pid => $qty) {
    mysqli_query($conn,
      "INSERT INTO order_items(order_id, product_id, qty)
       VALUES($order_id, $pid, $qty)"
    );
  }

 // Insert print jobs
if (isset($_SESSION['print_cart'])) {
  foreach ($_SESSION['print_cart'] as $pj) {
    $optStr = mysqli_real_escape_string($conn, $pj['options']);
    $file   = mysqli_real_escape_string($conn, $pj['filename']);
    $pages  = intval($pj['pages']);
    $price  = floatval($pj['price']);

    mysqli_query($conn,
      "INSERT INTO print_jobs
         (user_id, file_name, print_options, pages, price)
       VALUES
         ($user_id, '$file', '$optStr', $pages, $price)"
    );
  }


    // Clear print jobs after checkout
    unset($_SESSION['print_cart']);
  }

  // Clear products
    header("Location: payment.php?order_id=$order_id");
    exit;

}

?>

<section class="checkout">
  <h2>Checkout</h2>
  <form id="checkout-form" method="post">
    <label>Email:<br>
      <input type="email" name="email" required>
    </label><br><br>

    <label>Address:<br>
      <textarea name="address" rows="4" required></textarea>
    </label><br><br>

    <input type="hidden" name="cart" id="cart-data">

    <button type="button" onclick="submitOrder()">
      Place Order
    </button>
  </form>
</section>

<script>
// Grab cart from localStorage, inject into form, then submit
function submitOrder() {
  const cart = localStorage.getItem('edupress_cart');
  if (!cart || cart === '{}') {
    alert('Your cart is empty.');
    return;
  }
  document.getElementById('cart-data').value = cart;
  document.getElementById('checkout-form').submit();
}
</script>

<?php include 'footer.php'; ?>

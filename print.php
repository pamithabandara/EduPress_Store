<?php
session_start();
$page_title = 'Upload & Print – EduPress Store';
include 'header.php';

// Only allow logged in users
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check file and options
  if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $error = 'Please select a file to upload.';
  } else {
    $pages = intval($_POST['pages']);
    $options = $_POST['options'];

    if ($pages <= 0) {
      $error = 'Pages must be greater than 0.';
    } else {
      // Save file
      $origName = basename($_FILES['file']['name']);
      $safeName = uniqid()."_".$origName;
      move_uploaded_file($_FILES['file']['tmp_name'], __DIR__."/uploads/$safeName");

      // Calculate price (Rs.10 per page normal, Rs.15 per page color)
      $pricePerPage = $options === 'color' ? 15 : 10;
      $totalPrice = $pages * $pricePerPage;

      // Store print job in session cart
      if (!isset($_SESSION['print_cart'])) {
        $_SESSION['print_cart'] = [];
      }
      $_SESSION['print_cart'][] = [
        'filename' => $safeName,
        'orig_name' => $origName,
        'pages' => $pages,
        'options' => $options,
        'price' => $totalPrice
      ];

      $success = "File uploaded and added to your cart (Rs. $totalPrice)";
    }
  }
}
?>

<section class="print-upload">
  <h2>Upload Your Document to Print</h2>
  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <?php if ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
    <p><a href="cart.php">Go to Cart</a></p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Choose file (PDF/DOC):
      <input type="file" name="file" accept=".pdf,.doc,.docx" required>
    </label><br><br>
    <label>Number of pages:
      <input type="number" name="pages" min="1" required>
    </label><br><br>
    <label>Print Options:
      <select name="options" required>
        <option value="bw">Black & White</option>
        <option value="color">Color</option>
      </select>
    </label><br><br>
    <button type="submit">Upload and Add to Cart</button>
  </form>
</section>

<?php include 'footer.php'; ?>

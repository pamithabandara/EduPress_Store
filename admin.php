<?php
// admin.php
session_start();

// 1) Only allow admin@edupress.lk
if (!isset($_SESSION['user_id'])
  || ($_SESSION['user_email'] ?? '') !== 'admin@edupress.lk'
) {
  header('Location: login.php');
  exit;
}

$page_title = 'Admin – EduPress Store';
include 'header.php';

// 2) Connect to DB
$conn = mysqli_connect('localhost','root','', 'edupress_db');
if (!$conn) {
  die('DB error: '. mysqli_connect_error());
}

// 3) Handle “Add New Product”
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
  $n   = mysqli_real_escape_string($conn, $_POST['name']);
  $p   = floatval($_POST['price']);
  $img = basename($_FILES['image']['name']);
  move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/images/$img");
  mysqli_query($conn,
    "INSERT INTO products (name, price, image)
     VALUES ('$n', $p, '$img')"
  );
  header('Location: admin.php');
  exit;
}

// 4) Handle shipping action
if (isset($_GET['ship'])) {
  $oid = intval($_GET['ship']);
  mysqli_query($conn, "UPDATE orders SET shipped = 1 WHERE id = $oid");
  header('Location: admin.php');
  exit;
}

// 5) Fetch all orders
$ordersRes = mysqli_query($conn,
  "SELECT o.id, u.email, o.total, o.created_at, o.shipped
   FROM orders o
   JOIN users u ON u.id = o.user_id
   ORDER BY o.created_at DESC"
);

// 6) Fetch all print jobs
$pjRes = mysqli_query($conn,
  "SELECT
     pj.id,
     u.email          AS user_email,
     pj.file_name     AS file_name,
     pj.print_options AS print_options,
     pj.pages,
     pj.price,
     pj.created_at    AS date
   FROM print_jobs pj
   JOIN users u ON u.id = pj.user_id
   ORDER BY pj.created_at DESC"
);
if (!$pjRes) {
  die('Print jobs query error: ' . mysqli_error($conn));
}
?>

<!-- 7) Add‐New‐Product Form -->
<section class="admin-add">
  <h2>Add New Product</h2>
  <form method="post" action="admin.php" enctype="multipart/form-data">
    <label>Name:<br>
      <input type="text" name="name" required>
    </label><br><br>
    <label>Price:<br>
      <input type="number" step="0.01" name="price" required>
    </label><br><br>
    <label>Image:<br>
      <input type="file" name="image" accept="image/*" required>
    </label><br><br>
    <button type="submit">Add Product</button>
  </form>
</section>

<!-- 8) Orders Table -->
<section class="admin-orders">
  <h2>All Orders</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Total</th>
        <th>Date</th>
        <th>Shipped</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($o = mysqli_fetch_assoc($ordersRes)): ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['email']) ?></td>
        <td>Rs. <?= number_format($o['total'],2) ?></td>
        <td><?= date('d M Y H:i', strtotime($o['created_at'])) ?></td>
        <td><?= $o['shipped'] ? '✅' : '❌' ?></td>
        <td>
          <?php if (!$o['shipped']): ?>
            <a href="admin.php?ship=<?= $o['id'] ?>">Mark Shipped</a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>

<!-- 9) Print Jobs Table -->
<section class="admin-print-jobs">
  <h2>Print Jobs</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>User Email</th>
        <th>File</th>
        <th>Options</th>
        <th>Pages</th>
        <th>Price</th>
        <th>Date</th>
        <th>Download</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($pj = mysqli_fetch_assoc($pjRes)): ?>
      <tr>
        <td><?= $pj['id'] ?></td>
        <td><?= htmlspecialchars($pj['user_email']) ?></td>
        <td><?= htmlspecialchars($pj['file_name']) ?></td>
        <td><?= htmlspecialchars($pj['print_options']) ?></td>
        <td><?= $pj['pages'] ?></td>
        <td>Rs. <?= number_format($pj['price'],2) ?></td>
        <td><?= date('d M Y H:i', strtotime($pj['date'])) ?></td>
        <td><a href="download.php?job=<?= $pj['id'] ?>">Download</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>

<?php
// 10) Tear down
include 'footer.php';
mysqli_close($conn);
?>

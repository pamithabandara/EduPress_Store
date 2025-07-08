<?php
// login.php
session_start();
$page_title = 'Login – EduPress Store';
include 'header.php';

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
  header('Location: shop.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if (empty($email) || empty($pass)) {
    $error = 'Both fields are required.';
  } else {
    // 1) Connect to DB
    $conn = mysqli_connect('localhost','root','', 'edupress_db');
    if (!$conn) {
      die('DB error: '.mysqli_connect_error());
    }

    // 2) Lookup user
    $email_safe = mysqli_real_escape_string($conn, $email);
    $res = mysqli_query($conn,
      "SELECT id, pass_hash FROM users WHERE email = '$email_safe'"
    );

    if (mysqli_num_rows($res) === 1) {
      $user = mysqli_fetch_assoc($res);

      // 3) Verify password
      if (password_verify($pass, $user['pass_hash'])) {
        // 4) Success: set sessions and redirect
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_email'] = $email_safe;
        mysqli_close($conn);
        header('Location: shop.php');
        exit;
      }
    }

    // 5) Failure
    $error = 'Invalid email or password.';
    mysqli_close($conn);
  }
}
?>

<section class="auth-form">
  <h2>Login</h2>
  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form method="post">
    <label>Email:<br>
      <input type="email" name="email" required
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </label><br><br>

    <label>Password:<br>
      <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Login</button>
  </form>
  <p>No account? <a href="register.php">Register here</a>.</p>
</section>

<?php include 'footer.php'; ?>

<?php
// register.php
session_start();
$page_title = 'Register – EduPress Store';
include 'header.php';

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1) Collect & trim inputs
  $email     = trim($_POST['email'] ?? '');
  $password  = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';

  // 2) Basic validation
  if (empty($email) || empty($password) || empty($password2)) {
    $error = 'All fields are required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email.';
  } elseif ($password !== $password2) {
    $error = 'Passwords do not match.';
  } else {
    // 3) Connect to DB
    $conn = mysqli_connect('localhost','root','', 'edupress_db');
    if (!$conn) {
      die('DB connection error: ' . mysqli_connect_error());
    }

    // 4) Check if email exists
    $email_safe = mysqli_real_escape_string($conn, $email);
    $res = mysqli_query($conn,
      "SELECT id FROM users WHERE email = '$email_safe'"
    );
    if (mysqli_num_rows($res) > 0) {
      $error = 'Email is already registered.';
    } else {
      // 5) Hash password & insert
      $hash = password_hash($password, PASSWORD_DEFAULT);
      mysqli_query($conn,
        "INSERT INTO users (email, pass_hash)
         VALUES ('$email_safe', '$hash')"
      );
      mysqli_close($conn);
      echo '<p>Registration successful! <a href="login.php">Click here to log in</a>.</p>';
      include 'footer.php';
      exit;
    }
    mysqli_close($conn);
  }
}
?>

<section class="auth-form">
  <h2>Register</h2>
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

    <label>Confirm Password:<br>
      <input type="password" name="password2" required>
    </label><br><br>

    <button type="submit">Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Log in here</a>.</p>
</section>

<?php include 'footer.php'; ?>

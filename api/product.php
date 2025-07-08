<?php
// api/product.php
header('Content-Type: application/json');
$id = intval($_GET['id'] ?? 0);

// connect
$conn = mysqli_connect('localhost','root','', 'edupress_db');
if (!$conn) {
  http_response_code(500);
  echo json_encode(['error'=>'DB connection failed']);
  exit;
}

// fetch
$sql = "SELECT id, name, price FROM products WHERE id = $id";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
  http_response_code(404);
  echo json_encode(['error'=>'Not found']);
  exit;
}
$prod = mysqli_fetch_assoc($res);
$prod['price'] = floatval($prod['price']); // ensure number
echo json_encode($prod);

mysqli_close($conn);

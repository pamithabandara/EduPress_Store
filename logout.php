<?php
// logout.php
session_start();
// Clear all session data
$_SESSION = [];
session_destroy();
// Redirect back to home
header('Location: index.php');
exit;

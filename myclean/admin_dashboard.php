<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - MyClean</title>
</head>
<body>
  <h1>Welcome, Admin</h1>
  <p>This is the admin dashboard. You can assign bookings here.</p>
</body>
</html>
<?php
session_start();

// If user is not logged in, redirect to login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['user']['role'];

// Redirect based on role
if ($role === 'customer') {
    header("Location: customer_dashboard.php");
    exit();
} elseif ($role === 'cleaner') {
    header("Location: provider_dashboard.php");
    exit();
} else {
    echo "Unknown role. Please contact admin.";
}
?>

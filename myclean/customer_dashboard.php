<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
</head>
<body>
<h2>Welcome, <?php echo htmlspecialchars($user['name']); ?> (Customer)</h2>

<p><a href="booking.php">Make a Booking</a> | <a href="logout.php">Logout</a></p>

<h3>Your Bookings:</h3>

<?php
$user_id = $user['id'];
$sql = "SELECT * FROM bookings WHERE user_id = $user_id ORDER BY date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p>Date: " . $row['date'] . " | Time: " . $row['time'] . " | Status: " . $row['status'] . "</p>";
    }
} else {
    echo "<p>No bookings yet.</p>";
}
?>
</body>
</html>

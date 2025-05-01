
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'cleaner' && $_SESSION['user']['role'] !== 'provider')) {
    header("Location: home.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Fetch bookings (optional: filter based on assigned cleaner ID if needed)
$bookings = $conn->query("SELECT * FROM bookings WHERE status != 'completed' ORDER BY date, time");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Provider Dashboard - MyClean</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; background-color: #eaf6fb; }
        nav {
            background-color: #2980b9;
            padding: 12px 20px;
            display: flex;
            justify-content: center;
            gap: 30px;
            font-weight: bold;
        }
        nav a { color: white; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        .dashboard {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 { color: #2c3e50; }
        .profile {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f4f9fd;
            border-left: 5px solid #3498db;
        }
        .bookings table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .bookings th, .bookings td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .bookings th {
            background-color: #3498db;
            color: white;
        }
        footer {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<nav>
    <a href="home.php">Home</a>
    <a href="provider_dashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="dashboard">
    <div class="profile">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    </div>

    <div class="bookings">
        <h2>Assigned Bookings</h2>
        <?php if ($bookings->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Customer ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Method</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['time']; ?></td>
                        <td><?php echo $row['method']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; 2025 MyClean Services. All rights reserved.
</footer>

</body>
</html>


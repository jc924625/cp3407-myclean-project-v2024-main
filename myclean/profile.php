<?php
session_start();
include 'config.php';

// Redirect if not logged in or not a cleaner
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'cleaner') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $availability = $_POST['availability'];
    $service = $_POST['service_description'];

    $check = $conn->query("SELECT * FROM cleaner_profiles WHERE user_id = $user_id");

    if ($check->num_rows > 0) {
        // Update
        $sql = "UPDATE cleaner_profiles 
                SET availability = '$availability', service_description = '$service' 
                WHERE user_id = $user_id";
    } else {
        // Insert
        $sql = "INSERT INTO cleaner_profiles (user_id, availability, service_description) 
                VALUES ($user_id, '$availability', '$service')";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Profile updated successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch existing profile data
$result = $conn->query("SELECT * FROM cleaner_profiles WHERE user_id = $user_id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Cleaner Profile</title>
</head>
<body>
<h2>Edit Profile</h2>
<p><a href="provider_dashboard.php">← Back to Dashboard</a></p>

<form method="post">
    <label>Availability:</label><br>
    <textarea name="availability" rows="3" cols="40"><?php echo $data['availability'] ?? ''; ?></textarea><br><br>

    <label>Service Description:</label><br>
    <textarea name="service_description" rows="4" cols="40"><?php echo $data['service_description'] ?? ''; ?></textarea><br><br>

    <button type="submit">Save</button>
</form>

<p style="color:green;"><?php echo $message; ?></p>
</body>
</html>

<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $method = $_POST['method'];

    $sql = "INSERT INTO bookings (user_id, date, time, method, status) 
            VALUES ('$user_id', '$date', '$time', '$method', 'pending')";
    if ($conn->query($sql) === TRUE) {
        $message = "Booking submitted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book a Cleaning - MyClean</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eaf6fb;
            margin: 0;
            padding: 0;
        }

        .hero {
            position: relative;
            height: 300px;
            overflow: hidden;
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(2px);
            transform: scale(1.05);
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            text-shadow: 3px 3px 5px #000;
        }

        nav {
            background-color: #2980b9;
            padding: 12px 20px;
            display: flex;
            justify-content: center;
            gap: 30px;
            font-weight: bold;
        }

        nav a {
            color: white;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 600px;
            background: white;
            margin: 30px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background-color: #3498db;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            color: green;
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

<div class="hero">
    <img src="images\intro.png" alt="Banner">
    <div class="hero-text">
        Book Trusted Cleaners<br>
        <span style="font-size: 0.6em; font-weight: normal;">Get a spotless space, fast and easy</span>
    </div>
</div>

<nav>
    <a href="home.php">Home</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Cleaning Request Form</h2>
    <form method="post">
        <label for="date">Select Date:</label>
        <input type="date" name="date" required>

        <label for="time">Select Time:</label>
        <input type="time" name="time" required>

        <label for="method">Choose Cleaning Method:</label>
        <select name="method" required>
            <option value="">-- Select Method --</option>
            <option value="Surface Cleaning">Surface Cleaning</option>
            <option value="Deep Cleaning">Deep Cleaning</option>
            <option value="Steam Cleaning">Steam Cleaning</option>
        </select>

        <button type="submit">Submit Booking</button>
    </form>
    <div class="message"><?php echo $message; ?></div>
</div>

<footer>
    &copy; 2025 MyClean Services. All rights reserved.
</footer>

</body>
</html>


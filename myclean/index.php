<?php
session_start();
include 'config.php';

$message = "";

// Handle registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $message = "Email already registered.";
    } else {
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");
        $message = "Registration successful! Please login.";
    }
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            if ($user['role'] === 'provider' || $user['role'] === 'cleaner') {
                header("Location: provider_dashboard.php");
            } else {
                header("Location: booking.php");
            }
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "No user found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>MyClean - Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eaf6fb;
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

        .popup {
            display: none;
            position: fixed;
            z-index: 10;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.6);
        }

        .popup-content {
            background: white;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            margin: 100px auto;
            border-radius: 10px;
            position: relative;
        }

        .popup-content h3 {
            margin-top: 0;
            color: #2980b9;
        }

        .close {
            position: absolute;
            top: 10px; right: 15px;
            font-size: 22px;
            cursor: pointer;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        footer {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 40px;
        }
    </style>
    <script>
        function openPopup(id) {
            document.getElementById(id).style.display = 'block';
        }
        function closePopup(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
</head>
<body>

<div class="hero">
    <img src="images/banner.jpg" alt="Banner">
    <div class="hero-text">Welcome to MyClean</div>
</div>

<nav>
    <a href="home.php">Home</a>
    <?php if (!isset($_SESSION['user'])): ?>
        <a href="#" onclick="openPopup('loginModal')">Login</a>
        <a href="#" onclick="openPopup('registerModal')">Register</a>
    <?php else: ?>
        <?php if ($_SESSION['user']['role'] === 'customer'): ?>
            <a href="booking.php">Book Cleaning</a>
        <?php elseif ($_SESSION['user']['role'] === 'provider' || $_SESSION['user']['role'] === 'cleaner'): ?>
            <a href="provider_dashboard.php">Dashboard</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    <?php endif; ?>
</nav>

<!-- Login Modal -->
<div class="popup" id="loginModal">
    <div class="popup-content">
        <span class="close" onclick="closePopup('loginModal')">&times;</span>
        <h3>Login</h3>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</div>

<!-- Register Modal -->
<div class="popup" id="registerModal">
    <div class="popup-content">
        <span class="close" onclick="closePopup('registerModal')">&times;</span>
        <h3>Register</h3>
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="customer">Customer</option>
                <option value="cleaner">Cleaner</option>
            </select>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</div>

<footer>
    &copy; 2025 MyClean Services. All rights reserved.
</footer>
</body>
</html>

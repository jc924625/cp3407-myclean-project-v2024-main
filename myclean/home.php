
<?php
session_start();
include 'config.php';

$message = "";
$showRegisterPopup = false;
$secret_cleaner_code = "CLEANER2025";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        if ($role === 'cleaner') {
            $cleaner_code = $_POST['cleaner_code'];
            if ($cleaner_code !== $secret_cleaner_code) {
                $message = "Invalid Cleaner ID. Please contact the company for the correct code.";
                $showRegisterPopup = true;
            }
        }

        if (empty($message)) {
            $check = $conn->query("SELECT * FROM users WHERE email='$email'");
            if ($check->num_rows > 0) {
                $message = "Email already registered.";
                $showRegisterPopup = true;
            } else {
                $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')");
                $message = "Registration successful! Please login.";
            }
        }
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                    exit();
                } elseif ($user['role'] === 'provider' || $user['role'] === 'cleaner') {
                    header("Location: provider_dashboard.php");
                    exit();
                } else {
                    header("Location: booking.php");
                    exit();
                }
                } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "No user found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>MyClean - Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; background-color: #eaf6fb; }
        .hero { position: relative; height: 300px; overflow: hidden; }
        .hero img { width: 100%; height: 100%; object-fit: cover; filter: blur(2px); transform: scale(1.05); }
        .hero-text {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            color: white; text-align: center;
            font-size: 2.5em; font-weight: bold;
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
        nav a { color: white; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
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
        .popup-content h3 { margin-top: 0; color: #2980b9; }
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
        button:hover { background-color: #2980b9; }
        .container { padding: 30px; text-align: center; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        .card {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card img {
            width: 100%;
            border-radius: 6px;
            max-height: 160px;
            object-fit: cover;
        }
        footer {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 40px;
        }
        .hidden { display: none; }
    </style>
    <script>
        function openPopup(id) {
            document.getElementById(id).style.display = 'block';
        }
        function closePopup(id) {
            document.getElementById(id).style.display = 'none';
        }
        function toggleCleanerID(select) {
            var field = document.getElementById("cleaner-code-field");
            field.style.display = select.value === "cleaner" ? "block" : "none";
        }
    </script>
</head>
<body onload="<?php echo $showRegisterPopup ? 'openPopup(\'registerModal\')' : ''; ?>">

<div class="hero">
    <img src="images\intro.png" alt="Banner">
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
        <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
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
        <?php if (isset($_POST['login']) && !empty($message)): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>
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
            <select name="role" required onchange="toggleCleanerID(this)">
                <option value="">Select Role</option>
                <option value="customer">Customer</option>
                <option value="cleaner">Cleaner</option>
            </select>
            <div id="cleaner-code-field" class="hidden">
                <input type="text" name="cleaner_code" placeholder="Enter Cleaner ID">
            </div>
            <button type="submit" name="register">Register</button>
        </form>
        <?php if ($showRegisterPopup && !empty($message)): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <h2>Our Cleaning Methods</h2>
    <div class="grid">
        <div class="card">
            <img src="images/surface1.jpg" alt="Surface Cleaning">
            <h3>Surface Cleaning</h3>
            <p>Dusting and wiping of all visible surfaces with eco-friendly products.</p>
        </div>
        <div class="card">
            <img src="images/steam.jpg" alt="Deep Cleaning">
            <h3>Deep Cleaning</h3>
            <p>Thorough cleaning for kitchens, bathrooms, and hard-to-reach areas.</p>
        </div>
        <div class="card">
            <img src="images/deepcleani.avif" alt="Steam Cleaning">
            <h3>Steam Cleaning</h3>
            <p>Uses steam to sanitize floors, upholstery, and high-contact surfaces.</p>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 MyClean Services. All rights reserved.
</footer>

</body>
</html>

<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login • Food Engine Eats</title>
    <link rel="stylesheet" href="auth.css">
</head>

<body>

<img src="website-images/login.png" class="auth-bg">
<div class="auth-bg-overlay"></div>

<header>
    <div class="logo">
        <a href="draft.php">
            <img src="website-images/logo.png" alt="Food Engine Eats Logo">
        </a>
    </div>
</header>

<div class="auth-container">
    <form action="login.php" method="POST" class="auth-box">
        <h2>Login</h2>

        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

        <p>Don’t have an account? <a href="signup.html">Sign Up</a></p>
    </form>
</div>

</body>
</html>
<?php
exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    exit("Email and password required.");
}

$stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    exit("Invalid email or password.");
}

$stmt->bind_result($id, $full_name, $hashedPassword);
$stmt->fetch();

if (!password_verify($password, $hashedPassword)) {
    exit("Invalid email or password.");
}

$_SESSION['user_id'] = $id;
$_SESSION['full_name'] = $full_name;

header("Location: draft.php");
exit();
?>

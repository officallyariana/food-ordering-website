<?php
require_once "config.php";

$email = trim($_POST['email'] ?? "");
$password = $_POST['password'] ?? "";

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email";
    exit;
}

if (strlen($password) < 8) {
    echo "Password must be at least 8 characters";
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    echo "Email already registered";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users(email, password_hash) VALUES(?,?)");
$stmt->execute([$email, $hash]);

echo "success";


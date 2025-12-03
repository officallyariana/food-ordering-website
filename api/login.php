<?php
require_once "config.php";

$email = trim($_POST['email'] ?? "");
$password = $_POST['password'] ?? "";

$stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
$stmt->execute([$email]);

$user = $stmt->fetch();

if (!$user) {
    echo "Invalid credentials";
    exit;
}

if (!password_verify($password, $user['password_hash'])) {
    echo "Invalid credentials";
    exit;
}

echo "success";

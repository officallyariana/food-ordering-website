<?php
session_start();
require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    http_response_code(400);
    exit('Email and password required.');
}

$sql = "SELECT id, full_name, password FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    error_log("DB prepare error (login): " . $conn->error);
    http_response_code(500);
    exit('Server error.');
}

$stmt->bind_param('s', $email);
if (! $stmt->execute()) {
    error_log("DB execute error (login): " . $stmt->error);
    http_response_code(500);
    exit('Server error.');
}

$stmt->store_result();

if ($stmt->num_rows === 0) {
    http_response_code(401);
    exit('Invalid email or password.');
}

$stmt->bind_result($id, $full_name, $hashedPasswordFromDB);
$stmt->fetch();

if (empty($hashedPasswordFromDB)) {
    error_log("Login: password field empty for user: $email");
    http_response_code(500);
    exit('Server error.');
}

if (! password_verify($password, $hashedPasswordFromDB)) {
    http_response_code(401);
    exit('Invalid email or password.');
}

$_SESSION['user_id'] = $id;
$_SESSION['user'] = $full_name;

header('Location: draft.php');
exit();
?>

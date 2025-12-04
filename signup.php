<?php
session_start();
require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$full_name = trim($_POST['full_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';


if ($full_name === '' || $email === '' || $password === '') {
    http_response_code(400);
    exit('All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Invalid email format.');
}

$check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
if (! $check) {
    error_log("DB prepare error (signup-check): " . $conn->error);
    http_response_code(500);
    exit('Server error.');
}

$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    http_response_code(409);
    exit('This email is already registered.');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
if (!$hashedPassword) {
    http_response_code(500);
    exit('Password hashing failed.');
}

$insert = $conn->prepare("
    INSERT INTO users (full_name, email, password)
    VALUES (?, ?, ?)
");

if (! $insert) {
    error_log("DB prepare error (signup-insert): " . $conn->error);
    http_response_code(500);
    exit('Server error.');
}

$insert->bind_param("sss", $full_name, $email, $hashedPassword);

if (! $insert->execute()) {
    error_log("DB execute error (signup-insert): " . $insert->error);
    http_response_code(500);
    exit('Server error.');
}

$_SESSION['user_id'] = $insert->insert_id;
$_SESSION['user']    = $full_name;

header("Location: draft.html");
exit();
?>

<?php
// FILE: login.php
session_start();
require 'db.php'; // ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Basic validation
if ($email === '' || $password === '') {
    http_response_code(400);
    exit('Email and password required.');
}

// Prepare statement and check for errors
$sql = "SELECT id, full_name, password FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    error_log("DB prepare error (login): " . $conn->error);
    http_response_code(500);
    exit('Server error.');
}

// Bind, execute, and store result
$stmt->bind_param('s', $email);
if (! $stmt->execute()) {
    error_log("DB execute error (login): " . $stmt->error);
    http_response_code(500);
    exit('Server error.');
}

$stmt->store_result();

// If no row, fail fast (no fetch -> no undefined var)
if ($stmt->num_rows === 0) {
    // avoid telling which piece failed for security
    http_response_code(401);
    exit('Invalid email or password.');
}

// Bind results and fetch into well-named vars
$stmt->bind_result($id, $full_name, $hashedPasswordFromDB);
$stmt->fetch();

if (empty($hashedPasswordFromDB)) {
    // Defensive: shouldn't happen if row exists, but handle it
    error_log("Login: password field empty for user: $email");
    http_response_code(500);
    exit('Server error.');
}

// Verify password
if (! password_verify($password, $hashedPasswordFromDB)) {
    http_response_code(401);
    exit('Invalid email or password.');
}

// Successful login
$_SESSION['user_id'] = $id;
$_SESSION['user'] = $full_name;

// Redirect to your site/homepage
header('Location: draft.html');
exit();
?>

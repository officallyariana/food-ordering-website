<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/config.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) { echo json_encode(['success'=>false,'error'=>'Invalid input']); exit; }

$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

// server-side validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo json_encode(['success'=>false,'error'=>'Invalid email']); exit; }
if (!is_string($password) || strlen($password) < 8) { echo json_encode(['success'=>false,'error'=>'Password must be 8+ chars']); exit; }

try {
    // check duplicate
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) { echo json_encode(['success'=>false,'error'=>'Email already registered']); exit; }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO users (email, password_hash, created_at) VALUES (?, ?, NOW())');
    $ins->execute([$email, $hash]);

    echo json_encode(['success'=>true]);
    exit;
} catch (Exception $e) {
    echo json_encode(['success'=>false,'error'=>'Server error']);
    exit;
}

<?php
session_start();
$user = $_SESSION['user'] ?? null;

if (!$user) {
    echo json_encode(null);
    exit;
}

include "db.php";

$stmt = $conn->prepare("SELECT fullname, address, city, phone, notes FROM user_addresses WHERE user=? LIMIT 1");
$stmt->bind_param("s", $user);
$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result);
?>

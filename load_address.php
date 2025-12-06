<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) die(json_encode(null));

include "db.php";

$stmt = $conn->prepare("SELECT fullname, address, city, phone, notes FROM user_addresses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result ?? []);
?>

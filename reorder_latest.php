<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

include "db.php";

$user_id = $_SESSION['user_id'];

// Get latest order
$stmt = $conn->prepare("
    SELECT id FROM orders 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 1
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo json_encode([]);
    exit;
}

$order_id = $order['id'];

// Get all items in the last order
$stmt2 = $conn->prepare("
    SELECT item_name, price, qty, image 
    FROM order_items 
    WHERE order_id = ?
");

$stmt2->bind_param("i", $order_id);
$stmt2->execute();

$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($items);
?>
<?php
session_start();
require "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['cart']) || !isset($data['total'])) {
    echo json_encode(["status" => "error", "message" => "Invalid order data"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $data['cart'];
$total = $data['total'];

// STEP 1 — Insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();

$order_id = $stmt->insert_id;

// STEP 2 — Insert each item
$itemStmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, item_price, quantity) VALUES (?, ?, ?, ?)");

foreach ($cart as $item) {
    $itemStmt->bind_param(
        "isdi",
        $order_id,
        $item["name"],
        $item["price"],
        $item["qty"]
    );
    $itemStmt->execute();
}



echo json_encode([
    "status" => "success",
    "order_id" => $order_id,
    "message" => "Order placed successfully"
]);
?>

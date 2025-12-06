<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["success" => false, "error" => "Missing order_id"]);
    exit;
}

$order_id = intval($_GET['id']);

require "db.php";

$stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "Order not found or unauthorized"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT item_name AS name, price, quantity AS qty, image 
    FROM order_items 
    WHERE order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();

$cart = [];
while ($row = $items->fetch_assoc()) {
    $cart[] = $row;
}

echo json_encode(["success" => true, "cart" => $cart]);
exit;
?>

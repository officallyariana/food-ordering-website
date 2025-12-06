<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "Not logged in"]));
}

include "db.php";

if (!isset($_GET['order_id'])) {
    die(json_encode(["error" => "Missing order_id"]));
}

$order_id = intval($_GET['order_id']);
$user_id  = $_SESSION['user_id'];

// Fetch previous items
$stmt = $conn->prepare("
    SELECT item_name, price, qty, image
    FROM order_items
    WHERE order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
?>
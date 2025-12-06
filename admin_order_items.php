<?php
require "admin_protect.php";
require "db.php";

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
    echo "<p>Invalid order.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT item_name, price, quantity FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<p>No items found for this order.</p>";
    exit;
}

echo "<ul>";
while ($row = $res->fetch_assoc()) {
    $name = htmlspecialchars($row['item_name'], ENT_QUOTES, 'UTF-8');
    $qty  = (int)$row['quantity'];
    $price = number_format($row['price'], 2);
    echo "<li>{$name} × {$qty} — \${$price}</li>";
}
echo "</ul>";

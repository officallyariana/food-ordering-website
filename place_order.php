<?php
session_start();
$user = $_SESSION['user'] ?? null;
if (!$user) die("Not logged in");

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) die("Invalid data received");

$fullname = $data["fullname"];
$address  = $data["address"];
$city     = $data["city"];
$phone    = $data["phone"];
$notes    = $data["notes"];
$payment  = $data["payment"];
$cart     = $data["cart"];

$stmt = $conn->prepare("
    INSERT INTO user_addresses (username, fullname, address, city, phone, notes)
    VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE fullname=?, address=?, city=?, phone=?, notes=?
");

$stmt->bind_param(
    "ssssssssss",
    $user, $fullname, $address, $city, $phone, $notes,
    $fullname, $address, $city, $phone, $notes
);

$stmt->execute();

$total_price = array_sum(array_map(fn($i) => $i["price"] * $i["qty"], $cart));

$stmt = $conn->prepare("INSERT INTO orders (username, total_price, payment_method) VALUES (?, ?, ?)");
$stmt->bind_param("sds", $user, $total_price, $payment);
$stmt->execute();

$order_id = $stmt->insert_id;

$stmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, price, qty, image) VALUES (?, ?, ?, ?, ?)");

foreach ($cart as $item) {
    $stmt->bind_param(
        "isdss",
        $order_id,
        $item["name"],
        $item["price"],
        $item["qty"],
        $item["image"]
    );
    $stmt->execute();
}

echo "Order placed successfully!";
?>

<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("Not logged in");
}

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    die("Invalid JSON");
}

$fullname = $data["fullname"];
$address  = $data["address"];
$city     = $data["city"];
$phone    = $data["phone"];
$notes    = $data["notes"];
$payment  = $data["payment"];
$cart     = $data["cart"];

$stmt = $conn->prepare("
    INSERT INTO user_addresses (user_id, fullname, address, city, phone, notes)
    VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE fullname=?, address=?, city=?, phone=?, notes=?
");

$stmt->bind_param(
    "issssssssss",
    $user_id,
    $fullname, $address, $city, $phone, $notes,
    $fullname, $address, $city, $phone, $notes
);
$stmt->execute();

$total_amount = 0;
foreach ($cart as $i) {
    $total_amount += $i["price"] * $i["qty"];
}

$stmt = $conn->prepare("
    INSERT INTO orders (user_id, total_amount, payment_method)
    VALUES (?, ?, ?)
");
$stmt->bind_param("ids", $user_id, $total_amount, $payment);
$stmt->execute();

$order_id = $stmt->insert_id;


$stmt = $conn->prepare("
    INSERT INTO order_items (order_id, item_name, price, quantity, image)
    VALUES (?, ?, ?, ?, ?)
");

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

echo "OK";
?>

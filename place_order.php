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

// ⭐ FIX 1 — user_addresses table uses `user` column (varchar), NOT `user_id`
$stmt = $conn->prepare("
    INSERT INTO user_addresses (user, fullname, address, city, phone, notes)
    VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE fullname=?, address=?, city=?, phone=?, notes=?
");

$identifier = (string)$user_id; // convert to text for `user` column

$stmt->bind_param(
    "sssssssssss",
    $identifier, $fullname, $address, $city, $phone, $notes,
    $fullname, $address, $city, $phone, $notes
);
$stmt->execute();

// ⭐ FIX 2 — calculate total
$total_amount = 0;
foreach ($cart as $i) {
    $total_amount += $i["price"] * $i["qty"];
}

// ⭐ FIX 3 — INSERT into orders (this matches your DB)
$stmt = $conn->prepare("
    INSERT INTO orders (user_id, total_amount, payment_method)
    VALUES (?, ?, ?)
");
$stmt->bind_param("ids", $user_id, $total_amount, $payment);
$stmt->execute();

$order_id = $stmt->insert_id;

$stmt = $conn->prepare("
    INSERT INTO order_items (order_id, item_name, price, quantity)
    VALUES (?, ?, ?, ?)
");

foreach ($cart as $item) {
    $stmt->bind_param(
        "isdi",
        $order_id,
        $item["name"],
        $item["price"],
        $item["qty"] 
    );
    $stmt->execute();
}

echo "OK";
?>

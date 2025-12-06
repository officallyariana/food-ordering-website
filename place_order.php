<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) die("Not logged in");

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) die("Invalid JSON received");

// Extract data
$fullname = $data["fullname"];
$address  = $data["address"];
$city     = $data["city"];
$phone    = $data["phone"];
$notes    = $data["notes"];
$payment  = $data["payment"];
$cart     = $data["cart"];

// ---------- SAVE ADDRESS ----------
$stmt = $conn->prepare("
    INSERT INTO user_addresses (user_id, fullname, address, city, phone, notes)
    VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE fullname=?, address=?, city=?, phone=?, notes=?
");

$stmt->bind_param(
    "issssssssss",
    $user_id, $fullname, $address, $city, $phone, $notes,
    $fullname, $address, $city, $phone, $notes
);
$stmt->execute();


// ---------- CREATE ORDER ----------
$total_amount = array_sum(array_map(fn($i) => $i["price"] * $i["qty"], $cart));

$stmt = $conn->prepare("
    INSERT INTO orders (user_id, total_amount, payment_method)
    VALUES (?, ?, ?)
");
$stmt->bind_param("ids", $user_id, $total_amount, $payment);
$stmt->execute();

$order_id = $stmt->insert_id;


// ---------- ADD ORDER ITEMS ----------
$stmt = $conn->prepare("
    INSERT INTO order_items (order_id, item_name, price, qty, image)
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

echo "Order placed successfully!";
?>


<?php
session_start();
$user = $_SESSION['user'] ?? null;

if (!$user) {
    die("User not logged in.");
}

// DATABASE CONNECTION
$conn = new mysqli("localhost", "root", "", "food_engine_eats");

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// READ CART JSON
$cart = json_decode($_POST['orderData'], true);

if (!$cart || count($cart) === 0) {
    die("Cart is empty.");
}

$total = 0;
foreach ($cart as $item) {
    $total += $item["price"] * $item["qty"];
}

// 1) INSERT INTO orders
$stmt = $conn->prepare("INSERT INTO orders (user, total) VALUES (?, ?)");
$stmt->bind_param("sd", $user, $total);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// 2) INSERT ORDER ITEMS
$stmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, price, qty) VALUES (?, ?, ?, ?)");

foreach ($cart as $item) {
    $stmt->bind_param("isdi", $order_id, $item["name"], $item["price"], $item["qty"]);
    $stmt->execute();
}

$stmt->close();
$conn->close();

// CLEAR LOCAL CART USING JS
echo "
<script>
localStorage.removeItem('cart');
window.location.href = 'order_success.php?id=$order_id';
</script>
";
?>

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
session_start();
header("Content-Type: application/json");

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user = $_SESSION['user'];

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

$cart = $data["cart"] ?? [];
$total = $data["total"] ?? 0;

// Validate
if (empty($cart)) {
    echo json_encode(["status" => "error", "message" => "Cart is empty"]);
    exit;
}

// DB connection
$conn = new mysqli("localhost", "root", "", "food_engine_eats");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (user, total_price) VALUES (?, ?)");
$stmt->bind_param("sd", $user, $total);
$stmt->execute();

$order_id = $stmt->insert_id;
$stmt->close();

// Insert each item
$item_stmt = $conn->prepare("
    INSERT INTO order_items (order_id, item_name, price, qty, image)
    VALUES (?, ?, ?, ?, ?)
");

foreach ($cart as $item) {
    $item_stmt->bind_param(
        "issds",
        $order_id,
        $item["name"],
        $item["price"],
        $item["qty"],
        $item["image"]
    );
    $item_stmt->execute();
}

$item_stmt->close();
$conn->close();

// SUCCESS RESPONSE
echo json_encode([
    "status" => "success",
    "message" => "Order placed successfully",
    "order_id" => $order_id
]);

exit;
?>

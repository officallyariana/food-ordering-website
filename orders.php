<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="draft.css">
</head>
<body>

<h2>My Orders</h2>

<?php while ($order = $result->fetch_assoc()): ?>
<div class="order-box">
    <p><strong>Order #<?= $order['id'] ?></strong></p>
    <p>Total: $<?= number_format($order['total'], 2) ?></p>
    <p>Date: <?= $order['created_at'] ?></p>

    <details>
        <summary>View Items</summary>
        <ul>
        <?php
        $items = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $items->bind_param("i", $order['id']);
        $items->execute();
        $items_result = $items->get_result();

        while ($item = $items_result->fetch_assoc()):
        ?>
            <li><?= $item['item_name'] ?> × <?= $item['quantity'] ?> - $<?= $item['price'] ?></li>
        <?php endwhile; ?>
        </ul>
    </details>
</div>
<?php endwhile; ?>

<?php include "cart-ui.html"; ?>
<script src="draft.js"></script>
</body>
</html>

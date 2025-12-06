<?php
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$full_name = $_SESSION['full_name'] ?? "User";

if (!$user_id) {
    die("Not logged in");
}

include "db.php";

// FETCH ORDERS USING user_id (correct column)
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Orders</title>
<link rel="stylesheet" href="draft.css">
</head>
<body>

<header>
    <div class="logo">
        <a href="draft.php"><img src="website-images/logo.png"></a>
    </div>
    <nav>
        <span class="welcome-text">Welcome, <?= htmlspecialchars($full_name); ?></span>
        <button onclick="window.location.href='menu.php'" class="signup-btn">Menu</button>
        <button onclick="window.location.href='orders.php'" class="signup-btn">Orders</button>
        <form action="logout.php" method="POST" style="display:inline;">
            <button class="logout-btn">Logout</button>
        </form>
    </nav>
</header>

<h2>Your Order History</h2>

<?php while ($order = $orders->fetch_assoc()): ?>
<div class="order-box">
    <h3>Order #<?= $order['id'] ?></h3>
    <p>Total: $<?= number_format($order['total_amount'], 2) ?></p>
    <p>Status: <?= $order['status'] ?></p>
    <p>Payment: <?= $order['payment_method'] ?></p>
    <p>Date: <?= $order['created_at'] ?></p>

    <details>
        <summary>View Items</summary>
        <ul>
        <?php
            $stmt2 = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt2->bind_param("i", $order['id']);
            $stmt2->execute();
            $items = $stmt2->get_result();

            while ($i = $items->fetch_assoc()):
        ?>
            <li><?= $i['item_name'] ?> × <?= $i['qty'] ?> — $<?= number_format($i['price'], 2) ?></li>
        <?php endwhile; ?>
        </ul>
    </details>
</div>
<?php endwhile; ?>

</body>
</html>

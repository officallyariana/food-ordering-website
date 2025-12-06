<?php
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$full_name = $_SESSION['full_name'] ?? "Customer";

if (!$user_id) {
    header("Location: login.html");
    exit;
}

include "db.php";

$stmt = $conn->prepare("
    SELECT id, total_amount, status, payment_method, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Orders — Food Engine Eats</title>
<link rel="stylesheet" href="draft.css">

<style>
.order-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 10px;
}

.order-card {
    background: #fff;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 18px;
    box-shadow: 0 3px 14px rgba(0,0,0,0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-id {
    font-size: 1.2rem;
    font-weight: bold;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 8px;
    color: white;
    font-size: 0.9rem;
}
.status-pending { background: #ff9800; }
.status-completed { background: #4caf50; }
.status-cancelled { background: #e53935; }

.order-items {
    margin-top: 12px;
    background: #f7f7f7;
    padding: 12px;
    border-radius: 8px;
}
.order-items li {
    margin-bottom: 6px;
}

.view-btn {
    background: #8e4eac;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
}

.back-to-menu {
    display: inline-block;
    margin-bottom: 20px;
    color: #8e4eac;
    font-weight: 600;
}
</style>
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

<div class="order-container">
    <a class="back-to-menu" href="menu.php">← Back to Menu</a>
    <h2>Your Order History</h2>
    <?php if ($orders->num_rows === 0): ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>

    <?php while ($order = $orders->fetch_assoc()): ?>

    <div class="order-card">
        <div class="order-header">
            <span class="order-id">Order #<?= $order['id'] ?></span>

            <span class="status-badge 
                <?= $order['status'] == 'pending' ? 'status-pending' : 
                    ($order['status'] == 'completed' ? 'status-completed' : 'status-cancelled'); ?>">
                <?= ucfirst($order['status']) ?>
            </span>
        </div>

        <p><strong>Total:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
        <p><strong>Payment:</strong> <?= $order['payment_method'] ?></p>
        <p><strong>Date:</strong> <?= $order['created_at'] ?></p>
        <details>
            <summary><button class="view-btn">View Items</button></summary>
            <ul class="order-items">
            <?php
            $stmt2 = $conn->prepare("SELECT item_name, price, quantity FROM order_items WHERE order_id = ?");
            $stmt2->bind_param("i", $order['id']);
            $stmt2->execute();
            $items = $stmt2->get_result();
            while ($i = $items->fetch_assoc()):
            ?>
                <li><?= $i['item_name'] ?> × <?= $i['quantity'] ?> — $<?= number_format($i['price'], 2) ?></li>
            <?php endwhile; ?>
            </ul>
        </details>
    </div>
    <?php endwhile; ?>
</div>
</body>
</html>
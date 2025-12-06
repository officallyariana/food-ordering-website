<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    die("Access denied. Admin only.");
}

require "db.php";

$stmt = $conn->prepare("
    SELECT o.id, u.full_name, o.total_amount, o.status, o.payment_method, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard — Food Engine Eats</title>
<link rel="stylesheet" href="draft.css">

<style>
.admin-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 15px;
}

.order-card {
    background: #fff;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 18px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
}

.status-badge { 
    padding: 6px 12px; 
    border-radius: 6px;
    color: white;
}

.status-pending { background: #ff9800; }
.status-completed { background: #4caf50; }
.status-cancelled { background: #e53935; }

select {
    padding: 6px;
    border-radius: 6px;
}

.update-btn {
    background: #8e4eac;
    color: #fff;
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.update-btn:hover { background: #000; }

</style>
</head>

<body>

<header>
    <div class="logo">
        <a href="foodengine.php"><img src="website-images/logo.png"></a>
    </div>
    <nav>
        <button onclick="window.location.href='admin.php'" class="signup-btn">Admin Home</button>
        <button onclick="window.location.href='logout.php'" class="logout-btn">Logout</button>
    </nav>
</header>

<div class="admin-container">
<h2>All Orders</h2>

<?php while($order = $orders->fetch_assoc()): ?>

<div class="order-card">
    <div class="order-header">
        <h3>Order #<?= $order['id'] ?> — <?= $order['full_name'] ?></h3>

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
        <summary>View Items</summary>
        <ul>
            <?php
            $stmt2 = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt2->bind_param("i", $order['id']);
            $stmt2->execute();
            $items = $stmt2->get_result();

            while ($item = $items->fetch_assoc()):
            ?>
                <li><?= $item['item_name'] ?> × <?= $item['quantity'] ?> — $<?= number_format($item['price'], 2) ?></li>
            <?php endwhile; ?>
        </ul>
    </details>

    <form action="update_order_status.php" method="POST">
        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

        <select name="status">
            <option value="pending"   <?= $order['status']=='pending'?'selected':''; ?>>Pending</option>
            <option value="completed" <?= $order['status']=='completed'?'selected':''; ?>>Completed</option>
            <option value="cancelled" <?= $order['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
        </select>

        <button class="update-btn" type="submit">Update</button>
    </form>
</div>
<?php endwhile; ?>
</div>
</body>
</html>

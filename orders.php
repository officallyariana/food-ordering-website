<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id   = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'] ?? "Customer";

require "db.php";

$sql = "
    SELECT id, total_amount, status, payment_method, created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
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
    padding: 15px;
    font-family: "Poppins", sans-serif;
}

.order-card {
    background: #ffffff;
    padding: 20px;
    border-radius: 14px;
    margin-bottom: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.12);
    transition: 0.25s ease;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.18);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-id {
    font-size: 1.25rem;
    font-weight: 600;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 8px;
    color: #fff;
    font-weight: 500;
    text-transform: capitalize;
}

.status-pending { background: #ff9800; }
.status-completed { background: #4caf50; }
.status-cancelled { background: #e53935; }

details {
    margin-top: 12px;
    padding: 12px;
    background: #f7f7f7;
    border-radius: 10px;
}

details summary {
    cursor: pointer;
    font-weight: 600;
}

.order-items li {
    padding: 6px 0;
    border-bottom: 1px solid #ddd;
}

.order-items li:last-child {
    border-bottom: none;
}

.view-btn {
    background: #8e4eac;
    color: white;
    border: none;
    padding: 7px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
}

.reorder-btn {
    display: inline-block;
    background: #000;
    color: #fff;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    margin-top: 14px;
    cursor: pointer;
    transition: 0.3s ease;
}

.reorder-btn:hover {
    background: #8e4eac;
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
    <h2>Your Order History</h2>

<?php if ($orders->num_rows === 0): ?>
    <p>You have not placed any orders yet.</p>
<?php endif; ?>

<?php while ($order = $orders->fetch_assoc()): ?>

<div class="order-card">

    <div class="order-header">
        <span class="order-id">Order #<?= $order['id'] ?></span>

        <span class="status-badge 
            <?= $order['status'] == 'pending' ? 'status-pending' :
                ($order['status'] == 'completed' ? 'status-completed' : 'status-cancelled'); ?>">
            <?= htmlspecialchars($order['status']) ?>
        </span>
    </div>

    <div class="order-info">
        <p><strong>Total:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
        <p><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <p><strong>Date:</strong> <?= $order['created_at'] ?></p>
    </div>

    <details>
        <summary><button class="view-btn">View Items</button></summary>

        <ul class="order-items">
        <?php
            $items_sql = "SELECT item_name, price, quantity FROM order_items WHERE order_id = ?";
            $stmt_items = $conn->prepare($items_sql);
            $stmt_items->bind_param("i", $order['id']);
            $stmt_items->execute();
            $items = $stmt_items->get_result();

            while ($i = $items->fetch_assoc()):
        ?>
            <li><?= htmlspecialchars($i['item_name']) ?> × <?= $i['quantity'] ?> — $<?= number_format($i['price'], 2) ?></li>
        <?php endwhile; ?>
        </ul>
    </details>

    <button class="reorder-btn" data-order-id="<?= $order['id'] ?>">Order Again</button>
</div>

<?php endwhile; ?>
</div>

<script>
document.querySelectorAll(".reorder-btn").forEach(btn => {
    btn.addEventListener("click", async () => {
        const orderId = btn.dataset.orderId;

        const res = await fetch("reorder.php?id=" + encodeURIComponent(orderId));
        const data = await res.json();

        if (data.success) {
            localStorage.setItem("cart", JSON.stringify(data.cart));
            window.location.href = "checkout.php";
        } else {
            alert("Reorder failed: " + data.error);
        }
    });
});
</script>

</body>
</html>

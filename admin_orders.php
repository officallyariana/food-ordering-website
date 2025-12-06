<?php
require "admin_protect.php";
require "db.php";

function safe($str) {
    return htmlspecialchars($str ?? "", ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status   = $_POST['status'];

    $allowed = ['pending','completed','cancelled'];
    if (in_array($status, $allowed, true)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
    }
}

$sql = "
    SELECT o.id, o.user_id, o.total_amount, o.status, o.payment_method, o.created_at,
            u.full_name, u.email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
";
$orders = $conn->query($sql);
$admin_email = $_SESSION['admin_email'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders — Admin</title>
<link rel="stylesheet" href="draft.css">
<style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background: #111;
    color: #f5f5f5;
}
.admin-layout {
    display: flex;
    min-height: 100vh;
}
.admin-sidebar {
    width: 230px;
    background: #181818;
    padding: 18px 16px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.admin-sidebar .logo {
    text-align: center;
}
.admin-sidebar .logo img {
    height: 40px;
}
.admin-sidebar h3 {
    font-size: 1.1rem;
    margin-top: 10px;
    color: #aaa;
    text-align: center;
}
.admin-nav-links a {
    display: block;
    padding: 8px 10px;
    border-radius: 8px;
    color: #ddd;
    text-decoration: none;
    margin-bottom: 4px;
    font-size: 0.95rem;
}
.admin-nav-links a:hover,
.admin-nav-links a.active {
    background: #8e4eac;
    color: #fff;
}
.admin-main {
    flex: 1;
    background: #101010;
    padding: 18px 24px;
}
.admin-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}
.admin-topbar .admin-user {
    font-size: 0.95rem;
    color: #ccc;
}
.admin-topbar form {
    display: inline-block;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 18px;
    background: #181818;
    color: #eee;
}
.orders-table th, .orders-table td {
    border: 1px solid #333;
    padding: 8px 10px;
    font-size: 0.9rem;
}
.orders-table th {
    background: #202020;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    color: #fff;
    font-size: 0.8rem;
}
.status-pending { background: #ff9800; }
.status-completed { background: #4caf50; }
.status-cancelled { background: #e53935; }

.order-items-box {
    background: #202020;
    border-radius: 8px;
    padding: 8px;
    margin-top: 6px;
}
.order-items-box ul {
    margin-left: 18px;
}

.status-form {
    display: flex;
    gap: 6px;
    align-items: center;
}
.status-form select {
    padding: 4px 6px;
    background: #202020;
    color: #eee;
    border-radius: 4px;
    border: 1px solid #444;
}
.status-form button {
    padding: 4px 8px;
    border-radius: 6px;
    background: #8e4eac;
    color: #fff;
    border: none;
    cursor: pointer;
}
.status-form button:hover {
    background: #000;
}

.details-toggle {
    cursor: pointer;
    color: #8e4eac;
    text-decoration: underline;
    font-size: 0.85rem;
}
</style>
</head>
<body>

<div class="admin-layout">

    <aside class="admin-sidebar">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="website-images/logo.png" alt="Admin"></a>
        </div>
        <h3>Admin Panel</h3>
        <nav class="admin-nav-links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_orders.php" class="active">Orders</a>
            <a href="admin_users.php">Users</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <div class="admin-user">
                Logged in as <strong><?= safe($admin_email) ?></strong>
            </div>
            <form action="admin_logout.php" method="POST">
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <h2>All Orders</h2>

        <?php if ($orders->num_rows === 0): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($o = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$o['id'] ?></td>
                        <td>
                            <?= safe($o['full_name'] ?? 'Unknown'); ?><br>
                            <small><?= safe($o['email'] ?? ''); ?></small>
                        </td>
                        <td>$<?= number_format($o['total_amount'], 2); ?></td>
                        <td>
                            <span class="status-badge
                                <?= $o['status'] === 'pending' ? 'status-pending' :
                                    ($o['status'] === 'completed' ? 'status-completed' : 'status-cancelled'); ?>">
                                <?= safe($o['status']); ?>
                            </span>
                        </td>
                        <td><?= safe($o['payment_method']); ?></td>
                        <td><?= safe($o['created_at']); ?></td>
                        <td>
                            <span class="details-toggle" data-order-id="<?= (int)$o['id']; ?>">View Items</span>
                            <div class="order-items-box" id="items-<?= (int)$o['id']; ?>" style="display:none;"></div>
                        </td>
                        <td>
                            <form method="POST" class="status-form">
                                <input type="hidden" name="order_id" value="<?= (int)$o['id']; ?>">
                                <select name="status">
                                    <option value="pending"   <?= $o['status']==='pending'?'selected':''; ?>>Pending</option>
                                    <option value="completed" <?= $o['status']==='completed'?'selected':''; ?>>Completed</option>
                                    <option value="cancelled" <?= $o['status']==='cancelled'?'selected':''; ?>>Cancelled</option>
                                </select>
                                <button type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".details-toggle").forEach(el => {
        el.addEventListener("click", async () => {
            const orderId = el.dataset.orderId;
            const box = document.getElementById("items-" + orderId);

            if (box.style.display === "block") {
                box.style.display = "none";
                return;
            }

            if (!box.dataset.loaded) {
                const res = await fetch("admin_order_items.php?order_id=" + encodeURIComponent(orderId));
                const html = await res.text();
                box.innerHTML = html;
                box.dataset.loaded = "1";
            }

            box.style.display = "block";
        });
    });
});
</script>

</body>
</html>

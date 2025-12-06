<?php
require "admin_protect.php";
require "db.php";

$admin_email = $_SESSION['admin_email'] ?? 'admin';

$totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'] ?? 0;
$totalOrders = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'] ?? 0;
$totalRevenueRow = $conn->query("SELECT SUM(total_amount) AS s FROM orders WHERE status = 'completed'")->fetch_assoc();
$totalRevenue = $totalRevenueRow['s'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard — Food Engine Eats</title>
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

.admin-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 16px;
}
.admin-card {
    background: #181818;
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.6);
}
.admin-card h4 {
    margin-bottom: 6px;
    color: #a0a0a0;
}
.admin-card p {
    font-size: 1.4rem;
    font-weight: 600;
}

.section-title-admin {
    margin: 20px 0 10px;
    font-size: 1.2rem;
}
</style>
</head>
<body>

<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="website-images/logo.png" alt="Admin"></a>
        </div>
        <h3>Admin Panel</h3>
        <nav class="admin-nav-links">
            <a href="admin_dashboard.php" class="active">Dashboard</a>
            <a href="admin_orders.php">Orders</a>
            <a href="admin_users.php">Users</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <div class="admin-user">
                Logged in as <strong><?= htmlspecialchars($admin_email) ?></strong>
            </div>
            <form action="admin_logout.php" method="POST">
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <h2>Dashboard Overview</h2>

        <div class="admin-cards">
            <div class="admin-card">
                <h4>Total Users</h4>
                <p><?= (int)$totalUsers ?></p>
            </div>
            <div class="admin-card">
                <h4>Total Orders</h4>
                <p><?= (int)$totalOrders ?></p>
            </div>
            <div class="admin-card">
                <h4>Completed Revenue</h4>
                <p>$<?= number_format($totalRevenue, 2) ?></p>
            </div>
        </div>

        <h3 class="section-title-admin">Quick Links</h3>
        <p>
            <a href="admin_orders.php" style="color:#8e4eac;">View & manage all orders →</a>
        </p>
    </main>

</div>

</body>
</html>

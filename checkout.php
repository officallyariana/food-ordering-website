<?php
session_start();
$user = $_SESSION['full_name'] ?? null;
if (!$user) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout — Food Engine Eats</title>
<link rel="stylesheet" href="draft.css">

<style>
.checkout-wrapper {
    max-width: 1100px;
    margin: 30px auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.checkout-box {
    background: #fff;
    padding: 22px;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.12);
}

.checkout-item {
    display: flex;
    justify-content: space-between;
    background: #f7f7f7;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 8px;
}
.checkout-item-controls button {
    padding: 4px 8px;
    border: none;
    background: #8e4eac;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

.total-line {
    font-size: 1.3rem;
    font-weight: bold;
    text-align: right;
    margin-top: 10px;
}

.checkout-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #bbb;
    border-radius: 8px;
    margin-bottom: 10px;
}

#place-order {
    background: #8e4eac;
    color: white;
    padding: 14px;
    border-radius: 8px;
    border: none;
    font-size: 1.1rem;
    cursor: pointer;
    width: 100%;
}

.payment-methods label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}
</style>
</head>

<body>
<header>
    <div class="logo">
        <a href="foodengine.php"><img src="website-images/logo.png"></a>
    </div>
    <nav>
        <span class="welcome-text">Welcome, <?= htmlspecialchars($user); ?></span>
        <form action="logout.php" method="POST" style="display:inline;">
            <button class="logout-btn">Logout</button>
        </form>
    </nav>
</header>

<a href="menu.php" class="back-btn">← Back</a>
<section class="menu-page">
    <h2 class="section-title">Checkout</h2>

    <div class="checkout-wrapper">

        <div class="checkout-box">
            <h3>Your Order</h3>
            <div id="order-summary"></div>
            <p class="total-line" id="checkout-total"></p>
        </div>
        <div class="checkout-box">
            <h3>Delivery Info</h3>
            <form id="checkout-form">
                <input type="text" class="checkout-input" id="fullname" placeholder="Full Name" required>
                <input type="text" class="checkout-input" id="address" placeholder="Street Address" required>
                <input type="text" class="checkout-input" id="city" placeholder="City" required>
                <input type="text" class="checkout-input" id="phone" placeholder="Phone Number" required>
                <textarea class="checkout-input" id="notes" placeholder="Delivery Notes (optional)" rows="3"></textarea>
                <h3 style="margin-top: 10px;">Payment Method</h3>
                <div class="payment-methods">
                    <label><input type="radio" name="payment" value="Cash on Delivery" checked> Cash on Delivery</label>
                    <label><input type="radio" name="payment" value="Card Payment"> Credit / Debit Card</label>
                    <label><input type="radio" name="payment" value="PayPal"> PayPal (Coming soon)</label>
                </div>
                <button id="place-order" type="submit">Place Order</button>
            </form>
        </div>
    </div>
</section>

<div id="order-success-modal" class="order-success-modal">
    <div class="order-success-box">
        <h2>✔ Order Placed!</h2>
        <p>Your order has been submitted successfully.</p>
        <button id="view-orders-btn">View My Orders</button>
    </div>
</div>


<script src="checkout.js"></script>
</body>
</html>

<?php
session_start();
$user = $_SESSION['user'] ?? null;
if (!$user) {
    header("Location: login.html");
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
</head>

<body>

<header>
    <div class="logo">
        <a href="draft.php">
            <img src="website-images/logo.png" alt="">
        </a>
    </div>

    <nav>
        <span class="welcome-text">Welcome, <?= htmlspecialchars($user); ?>!</span>
        <form action="logout.php" method="POST" style="display:inline;">
            <button class="logout-btn">Logout</button>
        </form>

        <button class="cart-btn"><span id="cart-count">0</span></button>
    </nav>
</header>

<a href="menu.php" class="back-btn">← Back to Menu</a>

<section class="menu-page">
    <h2 class="section-title">Checkout</h2>

    <div class="checkout-container" style="
        max-width:600px;
        margin:auto;
        background:white;
        padding:20px;
        border-radius:12px;
        box-shadow:0 3px 10px rgba(0,0,0,0.15);
    ">

        <h3>Your Order Summary</h3>
        <div id="order-summary" style="margin-bottom:20px; font-size:1.1rem;">
            <!-- JS will insert items here -->
        </div>

        <h3>Enter Delivery Details</h3>

        <form id="checkout-form" style="display:flex; flex-direction:column; gap:12px;">
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
            <input type="text" id="address" name="address" placeholder="Delivery Address" required>
            <input type="text" id="phone" name="phone" placeholder="Phone Number" required>

            <button type="submit" id="place-order" style="
                background:black;
                color:white;
                padding:12px;
                border:none;
                border-radius:6px;
                cursor:pointer;
                font-size:1rem;
            ">
                Place Order
            </button>
        </form>

    </div>
</section>

<?php include "cart-ui.html"; ?>
<script src="draft.js"></script>
<script src="checkout.js"></script>

</body>
</html>

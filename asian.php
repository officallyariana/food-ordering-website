<?php
session_start();
$user = $_SESSION['full_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Engine Eats</title>
<link rel="stylesheet" href="draft.css">
</head>

<body>

<header>
    <div class="logo">
        <a href="foodengine.php">
            <img src="website-images/logo.png" alt="">
        </a>
    </div>

    <nav>
        <?php if ($user): ?>
            <span class="welcome-text">Welcome, <?= htmlspecialchars($user); ?>!</span>

            <form action="logout.php" method="POST" style="display:inline;">
                <button class="logout-btn">Logout</button>
            </form>
        <?php else: ?>
            <a href="login.php">Login</a>
            <button class="signup-btn" onclick="window.location.href='signup.html'">Sign Up</button>
        <?php endif; ?>

        <button class="cart-btn"><span id="cart-count">0</span></button>
        <a href="orders.php">My Orders</a>
    </nav>
</header>

<a href="menu.php" class="back-btn">← Back</a>

<section class="menu-page">
    <h2 class="section-title">Asian Cuisines</h2>

    <div class="menu-grid">
        <div class="card food-card" data-name="Bibimbap" data-price="10.00" data-image="website-images/bibimbap.png">
            <img src="website-images/bibimbap.png" alt="Bibimbap">
            <h3>Bibimbap</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Sushi Roll" data-price="10.50" data-image="website-images/sushi.jpg">
            <img src="website-images/sushi.jpg" alt="Sushi Roll">
            <h3>Sushi Roll</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Ramen" data-price="14.25" data-image="website-images/ramen.jpg">
            <img src="website-images/ramen.jpg" alt="Ramen">
            <h3>Ramen</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Pad Thai" data-price="12.00" data-image="website-images/pad-thai.jpg">
            <img src="website-images/pad-thai.jpg" alt="Pad Thai">
            <h3>Pad Thai</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Biryani" data-price="13.00" data-image="website-images/biryani.jpg">
            <img src="website-images/biryani.jpg" alt="Biryani">
            <h3>Biryani</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Roti Prata" data-price="8.00" data-image="website-images/roti-prata.jpg">
            <img src="website-images/roti-prata.jpg" alt="Roti Prata">
            <h3>Roti Prata</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Shawarma" data-price="9.00" data-image="website-images/shawarma.jpg">
            <img src="website-images/shawarma.jpg" alt="Shawarma">
            <h3>Shawarma</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Tonkatsu" data-price="11.50" data-image="website-images/tonkatsu.jpg">
            <img src="website-images/tonkatsu.jpg" alt="Tonkatsu">
            <h3>Tonkatsu</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>
    </div>
</section>

<div id="cart-modal" class="cart-modal">
    <div id="cart-overlay" class="cart-overlay"></div>

    <div class="cart-content">
        <h3>Your Cart</h3>

        <ul id="cart-items"></ul>

        <p id="cart-total">Total: $0.00</p>

        <div class="cart-buttons">
            <button id="clear-cart-btn" class="danger">Clear Cart</button>
            <button id="checkout-btn">Checkout</button>
            <button id="close-cart">Close</button>
        </div>
    </div>
</div>

<?php include "cart-ui.html"; ?>
<script src="draft.js"></script>
</body>
</html>


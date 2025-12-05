<?php
session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Caribbean Menu — Food Engine Eats</title>
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
        <?php if ($user): ?>
            <span class="welcome-text">Welcome, <?= htmlspecialchars($user); ?>!</span>
            <form action="logout.php" method="POST" style="display:inline;">
                <button class="logout-btn">Logout</button>
            </form>
        <?php else: ?>
            <a href="login.html">Login</a>
            <button class="signup-btn" onclick="window.location.href='signup.html'">Sign Up</button>
        <?php endif; ?>

        <button class="cart-btn"><span id="cart-count">0</span></button>
    </nav>
</header>

<a href="menu.php" class="back-btn">← Back</a>

<section class="menu-page">
    <h2 class="section-title">Caribbean Cuisines</h2>

    <div class="home-menu-grid">

        <div class="card food-card" data-name="Jerk Chicken" data-price="7.00" data-image="website-images/jerkchicken.jpg">
            <img src="website-images/jerkchicken.jpg" alt="">
            <h3>Jerk Chicken</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Curry Goat" data-price="7.00" data-image="foods/jamaican-curry-goat.jpg">
            <img src="foods/jamaican-curry-goat.jpg" alt="">
            <h3>Curry Goat</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Pepperpot Stew" data-price="15.00" data-image="foods/pepperpotstew.jpg">
            <img src="foods/pepperpotstew.jpg" alt="">
            <h3>Pepperpot Stew</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Rice and Peas" data-price="10.00" data-image="foods/riceandpeas.jpg">
            <img src="foods/riceandpeas.jpg" alt="">
            <h3>Rice and Peas</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Fried Plantains" data-price="3.00" data-image="foods/friedplaintain.jpg">
            <img src="foods/friedplaintain.jpg" alt="">
            <h3>Fried Plantains</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Ackee & Saltfish" data-price="7.00" data-image="foods/ackeeandsaltfish.jpg">
            <img src="foods/ackeeandsaltfish.jpg" alt="">
            <h3>Ackee & Saltfish</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Fritters" data-price="5.00" data-image="foods/fritters.jpg">
            <img src="foods/fritters.jpg" alt="">
            <h3>Fritters</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

    </div>
</section>

<?php include "cart-ui.html"; ?>

<script src="cart.js"></script>
</body>
</html>


<?php
session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>American Menu — Food Engine Eats</title>
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
    <h2 class="section-title">American Cuisines</h2>

    <div class="menu-grid">
        <div class="card food-card" data-name="Cheeseburger" data-price="10.00" data-image="website-images/cheeseburger.jpg">
            <img src="website-images/cheeseburger.jpg" alt="">
            <h3>Cheeseburger</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Fried Chicken" data-price="8.00" data-image="foods/friedchicken.png">
            <img src="foods/friedchicken.png" alt="">
            <h3>Fried Chicken</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Hot Chocolate" data-price="4.50" data-image="foods/hotchocolate.png">
            <img src="foods/hotchocolate.png" alt="">
            <h3>Hot Chocolate</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Mac and Cheese" data-price="5.00" data-image="foods/macandcheese.png">
            <img src="foods/macandcheese.png" alt="">
            <h3>Mac and Cheese</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Cornbread" data-price="3.00" data-image="foods/cornbread.png">
            <img src="foods/cornbread.png" alt="">
            <h3>Cornbread</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>
    </div>
</section>

<?php include "cart-ui.html"; ?>

<script src="draft.js"></script>
</body>
</html>

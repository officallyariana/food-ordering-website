<?php
session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Engine Eats | Menu</title>
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

        <a href="menu.php" class="active">Menu</a>
        <button class="cart-btn"><span id="cart-count">0</span></button>
    </nav>
</header>

<section class="menu-page">
    <h2 class="section-title">Explore Our Menu</h2>

    <div class="menu-grid">
        <div class="card">
            <img src="website-images/bibimbap.png" alt="">
            <h3>Asian Cuisines</h3>
            <button onclick="window.location.href='asian.php'">Explore Variety</button>
        </div>

        <div class="card">
            <img src="website-images/jerkchicken.jpg" alt="">
            <h3>Caribbean Cuisines</h3>
            <button onclick="window.location.href='caribbean.php'">Explore Variety</button>
        </div>

        <div class="card">
            <img src="website-images/american-cusine.jpg" alt="">
            <h3>American Cuisines</h3>
            <button onclick="window.location.href='american.php'">Explore Variety</button>
        </div>

        <div class="card">
            <img src="website-images/hispanic-foods.jpg" alt="">
            <h3>Hispanic Cuisines</h3>
            <button onclick="window.location.href='hispanic.php'">Explore Variety</button>
        </div>
    </div>
</section>

<section class="footer">
    <div class="credit">created by <span>arian s.</span> copyrights | all rights reserved!</div>
</section>

<?php include "cart-ui.html"; ?>
<script src="draft.js"></script>
</body>
</html>

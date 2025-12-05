<?php
session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hispanic Menu — Food Engine Eats</title>
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
    <h2 class="section-title">Hispanic Cuisines</h2>

    <div class="home-menu-grid">

        <div class="card food-card" data-name="Tacos" data-price="4.00" data-image="foods/taco.png">
            <img src="foods/taco.png" alt="">
            <h3>Tacos</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Empanadas" data-price="5.00" data-image="foods/empanadas.png">
            <img src="foods/empanadas.png" alt="">
            <h3>Empanadas</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Tamales" data-price="4.00" data-image="foods/tamales.png">
            <img src="foods/tamales.png" alt="">
            <h3>Tamales</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Gazpacho" data-price="7.00" data-image="foods/gazpachos.png">
            <img src="foods/gazpachos.png" alt="">
            <h3>Gazpacho</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

        <div class="card food-card" data-name="Mexican Tortas" data-price="8.00" data-image="foods/tortas.png">
            <img src="foods/tortas.png" alt="">
            <h3>Mexican Tortas</h3>
            <button class="add-to-cart">Add to Cart</button>
        </div>

    </div>
</section>

<?php include "cart-ui.html"; ?>
<script src="cart.js"></script>
</body>
</html>


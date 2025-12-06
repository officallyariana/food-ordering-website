<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null;
$user = $_SESSION['full_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Food Engine Eats</title>
    <link rel="stylesheet" href="draft.css" />
</head>

<body>

<header>
    <div class="logo">
        <a href="draft.php">
            <img src="website-images/logo.png" alt="Food Engine Eats Logo" />
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

        <?php if ($user): ?>
            <a href="orders.php">My Orders</a>
        <?php endif; ?>
    </nav>
</header>

<!-- HERO SECTION -->
<section class="hero">
    <div class="rating">
        <span>Google:</span>
        <span class="stars">★★★★☆</span>
        <span>(4.9)</span>
    </div>

    <h1>Order Now</h1>
    <p>where the restaurants and customers meet!</p>

    <button class="explore" onclick="window.location.href='menu.php'">
        Explore menu
    </button>

    <div class="image-slider">
        <img src="website-images/menu-1.png" alt="food 1" />
        <img src="website-images/menu-2.png" alt="food 2" />
        <img src="website-images/menu-3.png" alt="food 3" />
    </div>
</section>

<section id="menu-cards" class="menu-cards">
    <h2 class="section-title">OUR MENU</h2>

    <div class="home-menu-grid">
        <div class="card">
            <img src="website-images/bibimbap.png" alt="">
            <h3>Asian cuisines</h3>
            <button onclick="window.location.href='asian.php'">Explore Variety</button>
        </div>

        <div class="card">
            <img src="website-images/jerkchicken.jpg" alt="">
            <h3>Caribbean cuisines</h3>
            <button onclick="window.location.href='caribbean.php'">Explore Variety</button>
        </div>

        <div class="card">
            <img src="website-images/american-cusine.jpg" alt="">
            <h3>American cuisines</h3>
            <button onclick="window.location.href='american.php'">Explore Variety</button>
        </div>

        <div class="card">
            <img src="website-images/hispanic-foods.jpg" alt="">
            <h3>Hispanic cuisines</h3>
            <button onclick="window.location.href='hispanic.php'">Explore Variety</button>
        </div>
    </div>
</section>

<section class="top-picks">
    <h2 class="section-title">Top Picks</h2>

    <div class="top-picks-slider">
        <div class="pick-card">
            <img src="website-images/bibimbap.png" alt="">
            <p>Bibimbap</p>
        </div>

        <div class="pick-card">
            <img src="website-images/jerkchicken.jpg" alt="">
            <p>Jerk Chicken</p>
        </div>

        <div class="pick-card">
            <img src="website-images/cheeseburger.jpg" alt="">
            <p>Cheeseburger</p>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why-choose-us">
    <h2 class="section-title">WHY CHOOSE US?</h2>

    <div class="why-container">
        <div class="why-image">
            <img src="website-images/why us.png" alt="">
        </div>

        <div class="why-text">
            <div class="why-item"><h4>Fast Delivery</h4><p>Prompt and reliable delivery to your doorstep.</p></div>
            <div class="why-item"><h4>Owners manage their menus</h4><p>Owners have full control over their menu items and availability.</p></div>
            <div class="why-item"><h4>Better customer service</h4><p>We prioritize your satisfaction with fast and responsive support.</p></div>
            <div class="why-item"><h4>Cost effective</h4><p>Flexible pricing plans to suit your needs.</p></div>
            <div class="why-item"><h4>Variety</h4><p>Diverse food options from many cuisines.</p></div>
            <div class="why-item"><h4>User-friendly</h4><p>Simple and intuitive ordering experience.</p></div>
            <div class="why-item"><h4>Secure payments</h4><p>Multiple safe payment options.</p></div>
        </div>
    </div>
</section>

<section class="about-us">
    <div class="about-container">
        <div class="about-text">
            <h2>ABOUT US!</h2>
            <p>
                We connect restaurants and customers through a fast, modern, 
                and easy-to-use food ordering platform. 
            </p>
        </div>
        <div class="about-image">
            <img src="website-images/about-image.jpg" alt="">
        </div>
    </div>
</section>

<section class="footer">
    <a href="admin_login.html" class="admin-btn-modern">Admin Login</a>
    <div class="credit">created by <span>ariana s.</span> copyrights | all rights reserved!</div>
</section>

<?php include "cart-ui.html"; ?>
<script src="draft.js"></script>

</body>
</html>

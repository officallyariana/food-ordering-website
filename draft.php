<?php
session_start();
$user = $_SESSION['user'] ?? null;
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
            <img src="website-images/logo.png" alt="" />
        </a>
    </div>
    <nav>
        <?php if ($user): ?>
            <span class="welcome-text">Welcome, <?= htmlspecialchars($user) ?>!</span>
            
            <form action="logout.php" method="POST" style="display: inline;">
                <button class="logout-btn">Logout</button>
            </form>

        <?php else: ?>
            <a href="login.html">Login</a>
            <button class="signup-btn" onclick="window.location.href='signup.html'">Sign Up</button>
        <?php endif; ?>
        <button class="cart-btn"><span id="cart-count">0</span></button>
    </nav>
</header>

<section class="hero">
    <div class="rating">
    <span>Google:</span>
    <span class="stars">★★★★☆</span>
    <span>(4.9)</span>
    </div>
    <h1>Order Now</h1>
    <p>where the restaurants and customers meet!</p>
    <button class="explore" onclick="window.location.href='menu.php'">
    Explore menu</button>

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

    <section class="top-picks">
    <h2>Top Picks</h2>

    <div class="top-picks-slider">
        <div class="pick-card">
            <img src="website-images/bibimbap.png" alt="">
            <p>Bibimbap</p>
        </div>

        <div class="pick-card">
            <img src="website-images/jerkchicken.jpg" alt="">
            <p>jerk chicken</p>
        </div>

        <div class="pick-card">
            <img src="website-images/cheeseburger.jpg" alt="">
            <p>cheese burger</p>
        </div>
    </div>
</section>

<section class="why-choose-us">
    <h2 class="section-title">WHY CHOOSE US?</h2>

    <div class="why-container">
    <div class="why-image">
        <img src="website-images/why us.png" alt="">
    </div>
    <div class="why-text">
        <div class="why-item">
        <h4>Fast Delivery</h4>
        <p>Prompt and reliable delivery to your doorstep.</p>
    </div>
    <div class="why-item">
        <h4>owners are able to manage their menus.</h4>
        <p>Owners have full control over their menu items and availability.</p>
    </div>
    <div class="why-item">
        <h4>better customer service</h4>
        <p>We prioritize your satisfaction with fast and responsive support. 24/7 availability.</p>
    </div>
    <div class="why-item">
        <h4>cost effective for owners to use.</h4>
        <p>Flexible pricing plans to suit different business needs.</p>
    </div>
    <div class="why-item">
        <h4>variety of cuisines</h4>
        <p>Diverse range of food options from various cuisines to satisfy every palate.</p>
    </div>
    <div class="why-item">
        <h4>easy to use interface</h4>
        <p>User-friendly platform for seamless ordering experience.</p>
    </div>
    <div class="why-item">
        <h4>secure payment options</h4>
        <p>Multiple secure payment methods for safe transactions.</p>
    </div>
    </div>
</section>

<section class="about-us">
    <div class="about-container">
    <div class="about-text">
        <h2>ABOUT US! </h2>
        <p>I made this website to connect restaurants and customers seamlessly. I wanted it to
            be user friendly for everyone and anyone involved. Whether you're a restaurant owner looking to expand your reach or 
            a customer seeking delicious meals, this platform is designed to cater to your needs.
            We aim to address the growing demand for convenient food ordering and delivery services in the food industry.
        </p> 
    </div>
    <div class="about-image">
    <img src="website-images/about-image.jpg" alt="">
    </div>
</div>
</section>
<div class="cart-modal" id="cart-modal">
    <div class="cart-overlay" id="cart-overlay"></div>
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

<!-- footer starts here -->
<section class="footer">
    <div class="credit"> created by <span>arian s.</span> copyrights | all rights reserved! </div>
</section>
<!-- footer ends here -->
<?php include "cart-ui.html"; ?>
<script src="draft.js"></script>
</body>
</html>

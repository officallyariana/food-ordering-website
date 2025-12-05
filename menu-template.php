<?php
session_start();
$user = $_SESSION['user'] ?? null;

$cuisineTitle = $cuisineTitle ?? "Menu";
$dishes = $dishes ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?= $cuisineTitle ?> • Food Engine Eats</title>
    <link rel="stylesheet" href="menu.css" />
</head>
<body>

<header>
    <a href="draft.php" class="back-btn">← Back</a>
    <h1><?= $cuisineTitle ?></h1>
</header>

<section class="menu-section">
    <?php foreach ($dishes as $dish): ?>
        <div class="dish-card">
            <img src="<?= $dish['img'] ?>" alt="">
            <h3><?= $dish['name'] ?></h3>
            <p>$<?= number_format($dish['price'], 2) ?></p>
            <button>Add to Cart</button>
        </div>
    <?php endforeach; ?>
</section>

</body>
</html>

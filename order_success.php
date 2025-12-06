<?php
$order_id = $_GET['order_id'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
<title>Order Complete</title>
<link rel="stylesheet" href="draft.css">
</head>

<body>

<section style="text-align:center; padding:50px;">
    <h1> Order Successful!</h1>
    <p>Your order ID is: <strong>#<?= htmlspecialchars($order_id) ?></strong></p>

    <a href="foodengine.php" class="back-btn">Return to Home</a>
</section>

<?php include "cart-ui.html"; ?>
<script src="draft.js"></script>
</body>
</html>

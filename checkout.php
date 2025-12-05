<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>
<link rel="stylesheet" href="draft.css">
</head>
<body>

<h2>Order Summary</h2>

<div id="order-summary"></div>

<form id="checkout-form">
    <button type="submit" class="checkout-final-btn">Place Order</button>
</form>

<script src="checkout.js"></script>
</body>
</html>

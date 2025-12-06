<?php
session_start();
require "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] != 1) {
    die("Access denied.");
}

$order_id = $_POST["order_id"] ?? null;
$status   = $_POST["status"] ?? null;

if (!$order_id || !$status) {
    die("Missing data.");
}

$stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

header("Location: admin.php");
exit;
?>

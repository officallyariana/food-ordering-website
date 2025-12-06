<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["user"])) {
    echo json_encode(["error" => "not logged in"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "food_db");

if ($conn->connect_error) {
    echo json_encode(["error" => "db failed"]);
    exit;
}

$user = $_SESSION["user"];

$stmt = $conn->prepare("SELECT fullname, address, city, phone, notes FROM users WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($fullname, $address, $city, $phone, $notes);
$stmt->fetch();

echo json_encode([
    "fullname" => $fullname,
    "address" => $address,
    "city" => $city,
    "phone" => $phone,
    "notes" => $notes
]);

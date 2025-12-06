<?php
$host = "localhost";
$user = "root";
$pass = "wvT==JfkTjT7";
$dbname = "foodengine";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

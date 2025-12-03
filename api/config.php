<?php
// File: config.php
declare(strict_types=1);
$dbHost = 'localhost';
$dbName = 'foodengine';
$dbUser = 'root';
$dbPass = ''; 

$dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {

    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Database connection failed';
    exit;
}


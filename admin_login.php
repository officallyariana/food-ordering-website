<?php
session_start();
require "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, admin_email, admin_password 
                            FROM admins WHERE admin_email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        exit("Wrong admin email or password.");
    }

    $stmt->bind_result($id, $admin_email, $hash);
    $stmt->fetch();

    if (!password_verify($password, $hash)) {
        exit("Wrong admin email or password.");
    }

    $_SESSION['admin_id'] = $id;
    $_SESSION['admin_email'] = $admin_email;

    header("Location: admin_dashboard.php");
    exit;
}
?>


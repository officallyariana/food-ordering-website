<?php
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit;
}

require "db.php";
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    exit("Username and password required.");
}

$stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    exit("Invalid admin login.");
}

$stmt->bind_result($id, $uname, $hashedPassword);
$stmt->fetch();

if (!password_verify($password, $hashedPassword)) {
    exit("Invalid admin login.");
}

// SAVE ADMIN SESSION
$_SESSION["admin_id"] = $id;
$_SESSION["admin_username"] = $uname;

header("Location: admin.php");
exit();
?>

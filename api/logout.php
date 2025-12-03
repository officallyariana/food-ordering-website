<?php
// File: logout.php
declare(strict_types=1);
session_start();

// Clear session and cookies
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'] ?? '/', $params['domain'] ?? '', (bool)($params['secure'] ?? false), (bool)($params['httponly'] ?? false)
    );
}
session_destroy();

// Redirect to homepage (adjust path as needed)
header('Location: /index.html');
exit;

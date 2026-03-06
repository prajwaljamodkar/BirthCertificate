<?php
/**
 * auth/logout.php — Destroys the session and redirects to login.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();

header('Location: ' . BASE_URL . 'auth/login.php');
exit;

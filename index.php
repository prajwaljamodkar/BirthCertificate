<?php
/**
 * index.php — Entry point.
 * Redirects authenticated users to their role-appropriate dashboard,
 * otherwise sends them to the login page.
 */

define('BASE_URL', '/');

require_once __DIR__ . '/includes/session.php';

if (isLoggedIn()) {
    $role = currentRole();
    if ($role === 'authority1') {
        header('Location: ' . BASE_URL . 'authority1/dashboard.php');
    } elseif ($role === 'authority2') {
        header('Location: ' . BASE_URL . 'authority2/dashboard.php');
    } else {
        header('Location: ' . BASE_URL . 'user/dashboard.php');
    }
} else {
    header('Location: ' . BASE_URL . 'auth/login.php');
}
exit;

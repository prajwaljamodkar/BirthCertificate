<?php
/**
 * Session management and role-checking helpers.
 * Include this file at the top of every protected page.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Returns true when a user is logged in.
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Returns the current user's role, or '' if not logged in.
 */
function currentRole(): string {
    return $_SESSION['role'] ?? '';
}

/**
 * Redirect to login if not authenticated.
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit;
    }
}

/**
 * Redirect with an error if the current user doesn't have the expected role.
 */
function requireRole(string $role): void {
    requireLogin();
    if (currentRole() !== $role) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}

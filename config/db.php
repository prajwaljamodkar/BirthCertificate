<?php
/**
 * Database Configuration
 * Connects to PostgreSQL using PDO.
 * Values are read from environment variables when present (Docker / production),
 * and fall back to the constants below for plain PHP / development setups.
 */

define('DB_HOST',   getenv('DB_HOST') ?: 'localhost');
define('DB_PORT',   getenv('DB_PORT') ?: '5432');
define('DB_NAME',   getenv('DB_NAME') ?: 'birthcertificate');
define('DB_USER',   getenv('DB_USER') ?: 'postgres');
define('DB_PASS',   getenv('DB_PASS') ?: 'yourpassword');   // set via DB_PASS env var or change this default

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            DB_HOST, DB_PORT, DB_NAME
        );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Show a user-friendly error; avoid leaking credentials
            die('<p style="color:red;font-family:sans-serif;">Database connection failed. '
              . 'Please check <code>config/db.php</code> and ensure PostgreSQL is running.</p>');
        }
    }
    return $pdo;
}

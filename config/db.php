<?php
// config/db.php

// Load environment variables from .env
$dotenv = parse_ini_file(__DIR__ . '/../.env');

// Database connection settings
define('DB_HOST',   getenv('DB_HOST') ?: $dotenv['DB_HOST'] ?? 'localhost');
define('DB_PORT',   getenv('DB_PORT') ?: $dotenv['DB_PORT'] ?? '5432');
define('DB_NAME',   getenv('DB_NAME') ?: $dotenv['DB_NAME'] ?? 'postgres');
define('DB_USER',   getenv('DB_USER') ?: $dotenv['DB_USER'] ?? 'postgres');
define('DB_PASS',   getenv('DB_PASS') ?: $dotenv['DB_PASS'] ?? '');

try {
    $pdo = new PDO(
        "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";sslmode=disable",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
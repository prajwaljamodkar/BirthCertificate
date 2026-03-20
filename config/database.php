<?php
$supabaseUrl = getenv('DB_HOST');
$supabasePassword = getenv('DB_PASS');

// PostgreSQL connection for Supabase
$conn = new PDO(
    "pgsql:host=" . $supabaseUrl . ";port=5432;dbname=postgres",
    'postgres',
    $supabasePassword
);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
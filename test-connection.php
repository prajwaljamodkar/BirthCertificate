<?php
require_once 'config/db.php';

try {
    // Test connection
    $result = $pdo->query("SELECT COUNT(*) as user_count FROM users");
    $row = $result->fetch();
    echo "✓ Connected to Supabase!\n";
    echo "Users count: " . $row['user_count'] . "\n";
    
    // Verify seed data
    $result = $pdo->query("SELECT username FROM users WHERE role='authority1'");
    $authority = $result->fetch();
    echo "Authority 1 user: " . ($authority ? $authority['username'] : 'Not found') . "\n";
    
} catch (PDOException $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
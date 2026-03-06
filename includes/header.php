<?php
/**
 * Common HTML header and navigation bar.
 * $pageTitle must be set by the including page.
 */
$pageTitle = $pageTitle ?? 'Birth Certificate System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f6f8; color: #333; }
        header { background: #1a73e8; color: #fff; padding: 14px 24px;
                 display: flex; align-items: center; justify-content: space-between; }
        header h1 { font-size: 1.3rem; }
        nav a { color: #fff; text-decoration: none; margin-left: 18px;
                font-size: 0.9rem; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 960px; margin: 30px auto; padding: 0 20px; }
        .card { background: #fff; border-radius: 6px; padding: 28px;
                box-shadow: 0 2px 6px rgba(0,0,0,.1); margin-bottom: 20px; }
        .btn { display: inline-block; padding: 9px 18px; border-radius: 4px;
               border: none; cursor: pointer; font-size: 0.9rem; text-decoration: none; }
        .btn-primary { background: #1a73e8; color: #fff; }
        .btn-primary:hover { background: #1558b0; }
        .btn-success { background: #34a853; color: #fff; }
        .btn-success:hover { background: #2a8a42; }
        .btn-danger  { background: #ea4335; color: #fff; }
        .btn-danger:hover  { background: #c5352a; }
        .btn-secondary { background: #5f6368; color: #fff; }
        .btn-secondary:hover { background: #494d52; }
        .alert { padding: 12px 16px; border-radius: 4px; margin-bottom: 16px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info    { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 10px 14px; text-align: left;
                              border-bottom: 1px solid #e0e0e0; }
        table th { background: #f0f4ff; }
        .status-badge { padding: 3px 10px; border-radius: 12px; font-size: 0.8rem;
                        font-weight: bold; white-space: nowrap; }
        .status-pending      { background: #fff3cd; color: #856404; }
        .status-approved_auth1 { background: #cce5ff; color: #004085; }
        .status-approved_auth2 { background: #d4edda; color: #155724; }
        .status-verified     { background: #155724; color: #fff; }
        .status-rejected_auth1,
        .status-rejected_auth2 { background: #f8d7da; color: #721c24; }
        label { display: block; margin-top: 14px; font-weight: bold; font-size: 0.9rem; }
        input[type=text], input[type=date], input[type=password],
        select, textarea {
            width: 100%; padding: 8px 10px; margin-top: 4px; border-radius: 4px;
            border: 1px solid #ccc; font-size: 0.95rem;
        }
        .radio-group { margin-top: 6px; }
        .radio-group label { display: inline; font-weight: normal;
                             margin-right: 16px; margin-top: 0; }
        .form-actions { margin-top: 22px; }
    </style>
</head>
<body>
<header>
    <h1>🏛️ Birth Certificate System</h1>
    <nav>
        <?php if (isLoggedIn()): ?>
            <?php $role = currentRole(); ?>
            <?php if ($role === 'user'): ?>
                <a href="<?php echo BASE_URL; ?>user/dashboard.php">My Applications</a>
                <a href="<?php echo BASE_URL; ?>user/apply.php">Apply</a>
            <?php elseif ($role === 'authority1'): ?>
                <a href="<?php echo BASE_URL; ?>authority1/dashboard.php">Dashboard</a>
            <?php elseif ($role === 'authority2'): ?>
                <a href="<?php echo BASE_URL; ?>authority2/dashboard.php">Dashboard</a>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>auth/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>)</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>auth/login.php">Login</a>
            <a href="<?php echo BASE_URL; ?>auth/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">

<?php
/**
 * auth/register.php — Registration page for regular users only.
 * Authority accounts are pre-seeded via database.sql.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

// Already logged in? Redirect.
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username']  ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $password  = $_POST['password']  ?? '';
    $confirm   = $_POST['confirm']   ?? '';

    if ($username === '' || $full_name === '' || $password === '') {
        $error = 'All fields are required.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $pdo = getDB();
        // Check for duplicate username
        $check = $pdo->prepare('SELECT id FROM users WHERE username = :u');
        $check->execute([':u' => $username]);
        if ($check->fetch()) {
            $error = 'Username already taken. Please choose another.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                'INSERT INTO users (username, password, full_name, role) VALUES (:u, :p, :n, :r)'
            );
            $stmt->execute([
                ':u' => $username,
                ':p' => $hash,
                ':n' => $full_name,
                ':r' => 'user',
            ]);
            $success = 'Registration successful! You can now <a href="login.php">login</a>.';
        }
    }
}

$pageTitle = 'Register — Birth Certificate System';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card" style="max-width:440px;margin:40px auto;">
    <h2 style="margin-bottom:20px;">Create an Account</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="post" action="">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" required
               value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" />

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required
               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />

        <label for="password">Password <small>(min 6 chars)</small></label>
        <input type="password" id="password" name="password" required />

        <label for="confirm">Confirm Password</label>
        <input type="password" id="confirm" name="confirm" required />

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="width:100%;">Register</button>
        </div>
    </form>
    <?php endif; ?>

    <p style="margin-top:16px;font-size:0.9rem;">
        Already have an account? <a href="login.php">Login here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
/**
 * auth/login.php — Login page for all roles.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

// Already logged in? Redirect.
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT id, username, password, full_name, role FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role']     = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'authority1') {
                header('Location: ' . BASE_URL . 'authority1/dashboard.php');
            } elseif ($user['role'] === 'authority2') {
                header('Location: ' . BASE_URL . 'authority2/dashboard.php');
            } else {
                header('Location: ' . BASE_URL . 'user/dashboard.php');
            }
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

$pageTitle = 'Login — Birth Certificate System';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card" style="max-width:400px;margin:40px auto;">
    <h2 style="margin-bottom:20px;">Login</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required
               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
        </div>
    </form>

    <p style="margin-top:16px;font-size:0.9rem;">
        Don't have an account?
        <a href="<?php echo BASE_URL; ?>auth/register.php">Register here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

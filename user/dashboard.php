<?php
/**
 * user/dashboard.php — Shows a list of the authenticated user's applications.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

requireRole('user');

$pdo  = getDB();
$stmt = $pdo->prepare(
    'SELECT id, fname, mname, lname, birthdate, status, applied_at
       FROM applications
      WHERE user_id = :uid
   ORDER BY applied_at DESC'
);
$stmt->execute([':uid' => $_SESSION['user_id']]);
$applications = $stmt->fetchAll();

$statusLabels = [
    'pending'          => 'Pending',
    'approved_auth1'   => 'Approved by Authority 1',
    'rejected_auth1'   => 'Rejected by Authority 1',
    'approved_auth2'   => 'Approved by Authority 2',
    'rejected_auth2'   => 'Rejected by Authority 2',
    'verified'         => 'Verified ✓',
];

$pageTitle = 'My Applications — Birth Certificate System';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>My Applications</h2>
    <p style="margin-top:8px;color:#555;">
        Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></strong>!
    </p>
    <div style="margin-top:16px;">
        <a href="<?php echo BASE_URL; ?>user/apply.php" class="btn btn-primary">+ New Application</a>
    </div>
</div>

<div class="card">
    <?php if (empty($applications)): ?>
        <div class="alert alert-info">You have not submitted any applications yet.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Birth Date</th>
                    <th>Applied At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?php echo (int)$app['id']; ?></td>
                    <td><?php echo htmlspecialchars(implode(' ', array_filter([$app['fname'], $app['mname'], $app['lname']]))); ?></td>                    <td><?php echo htmlspecialchars($app['birthdate']); ?></td>
                    <td><?php echo htmlspecialchars($app['applied_at']); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo htmlspecialchars($app['status']); ?>">
                            <?php echo htmlspecialchars($statusLabels[$app['status']] ?? $app['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($app['status'] === 'verified'): ?>
                            <a href="<?php echo BASE_URL; ?>user/download.php?id=<?php echo (int)$app['id']; ?>"
                               class="btn btn-success" style="font-size:0.8rem;">
                                ⬇ Download Certificate
                            </a>
                        <?php else: ?>
                            <span style="color:#888;font-size:0.85rem;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

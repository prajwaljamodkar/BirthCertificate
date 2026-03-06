<?php
/**
 * authority2/dashboard.php — Lists all applications approved by Authority 1.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

requireRole('authority2');

$pdo  = getDB();
$stmt = $pdo->query(
    "SELECT a.id, a.fname, a.mname, a.lname, a.birthdate, a.bplace, a.applied_at,
            a.auth1_action_at, u.username AS applicant
       FROM applications a
       JOIN users u ON u.id = a.user_id
      WHERE a.status = 'approved_auth1'
   ORDER BY a.auth1_action_at ASC"
);
$applications = $stmt->fetchAll();

$pageTitle = 'Authority 2 — Applications for Final Approval';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Applications for Final Approval</h2>
    <p style="margin-top:6px;color:#555;font-size:0.9rem;">
        These applications have been approved by Authority 1 and are awaiting your review.
    </p>
    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-success" style="margin-top:12px;">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <?php if (empty($applications)): ?>
        <div class="alert alert-info">No applications awaiting final approval at this time.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Applicant</th>
                    <th>Name on Certificate</th>
                    <th>Birth Date</th>
                    <th>Auth 1 Approved At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?php echo (int)$app['id']; ?></td>
                    <td><?php echo htmlspecialchars($app['applicant']); ?></td>
                    <td><?php echo htmlspecialchars(implode(' ', array_filter([$app['fname'], $app['mname'], $app['lname']]))); ?></td>                    <td><?php echo htmlspecialchars($app['birthdate']); ?></td>
                    <td><?php echo htmlspecialchars($app['auth1_action_at'] ?? '—'); ?></td>
                    <td>
                        <a href="view_application.php?id=<?php echo (int)$app['id']; ?>"
                           class="btn btn-primary" style="font-size:0.8rem;">View &amp; Verify</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
/**
 * authority1/dashboard.php — Lists all pending applications.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

requireRole('authority1');

$pdo  = getDB();
$stmt = $pdo->query(
    "SELECT a.id, a.fname, a.mname, a.lname, a.birthdate, a.bplace, a.applied_at,
            u.username AS applicant
       FROM applications a
       JOIN users u ON u.id = a.user_id
      WHERE a.status = 'pending'
   ORDER BY a.applied_at ASC"
);
$applications = $stmt->fetchAll();

$pageTitle = 'Authority 1 — Pending Applications';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Pending Applications</h2>
    <p style="margin-top:6px;color:#555;font-size:0.9rem;">
        Review and verify applications submitted by users.
    </p>
    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-success" style="margin-top:12px;">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <?php if (empty($applications)): ?>
        <div class="alert alert-info">No pending applications at this time.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Applicant</th>
                    <th>Name on Certificate</th>
                    <th>Birth Date</th>
                    <th>Birth Place</th>
                    <th>Applied At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?php echo (int)$app['id']; ?></td>
                    <td><?php echo htmlspecialchars($app['applicant']); ?></td>
                    <td><?php echo htmlspecialchars(implode(' ', array_filter([$app['fname'], $app['mname'], $app['lname']]))); ?></td>                    <td><?php echo htmlspecialchars($app['birthdate']); ?></td>
                    <td><?php echo htmlspecialchars($app['bplace']); ?></td>
                    <td><?php echo htmlspecialchars($app['applied_at']); ?></td>
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

<?php
/**
 * authority2/view_application.php — Shows full application details for Authority 2 final review.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

requireRole('authority2');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . 'authority2/dashboard.php');
    exit;
}

$pdo  = getDB();
$stmt = $pdo->prepare(
    "SELECT a.*, u.username AS applicant, u.full_name AS applicant_fullname
       FROM applications a
       JOIN users u ON u.id = a.user_id
      WHERE a.id = :id AND a.status = 'approved_auth1'"
);
$stmt->execute([':id' => $id]);
$app = $stmt->fetch();

if (!$app) {
    header('Location: ' . BASE_URL . 'authority2/dashboard.php');
    exit;
}

$pageTitle = 'Final Review — Application #' . $id;
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Final Review — Application #<?php echo (int)$id; ?></h2>
    <a href="<?php echo BASE_URL; ?>authority2/dashboard.php" class="btn btn-secondary"
       style="margin-top:10px;font-size:0.85rem;">← Back to Dashboard</a>
</div>

<div class="card">
    <h3 style="margin-bottom:14px;">Personal Details</h3>
    <table>
        <tr><th style="width:220px">Full Name</th>
            <td><?php echo htmlspecialchars(implode(' ', array_filter([$app['fname'], $app['mname'], $app['lname']]))); ?></td></tr>
        <tr><th>Birth Date</th>
            <td><?php echo htmlspecialchars($app['birthdate']); ?></td></tr>
        <tr><th>Birth Place</th>
            <td><?php echo htmlspecialchars($app['bplace']); ?></td></tr>
        <tr><th>Gender</th>
            <td><?php echo htmlspecialchars($app['gender']); ?></td></tr>
        <tr><th>Father's Name</th>
            <td><?php echo htmlspecialchars($app['father_name']); ?></td></tr>
        <tr><th>Mother's Name</th>
            <td><?php echo htmlspecialchars($app['mother_name']); ?></td></tr>
        <tr><th>Religion</th>
            <td><?php echo htmlspecialchars($app['religion']); ?></td></tr>
        <tr><th>Category</th>
            <td><?php echo htmlspecialchars($app['category']); ?></td></tr>
        <tr><th>Applicant Username</th>
            <td><?php echo htmlspecialchars($app['applicant']); ?></td></tr>
        <tr><th>Applied At</th>
            <td><?php echo htmlspecialchars($app['applied_at']); ?></td></tr>
    </table>
</div>

<div class="card">
    <h3 style="margin-bottom:14px;">Authority 1 Review</h3>
    <table>
        <tr><th style="width:220px">Action Taken At</th>
            <td><?php echo htmlspecialchars($app['auth1_action_at'] ?? '—'); ?></td></tr>
        <tr><th>Remarks</th>
            <td><?php echo nl2br(htmlspecialchars($app['auth1_remarks'] ?? '—')); ?></td></tr>
    </table>
</div>

<div class="card">
    <h3 style="margin-bottom:14px;">Your Final Decision</h3>
    <form method="post" action="approve.php">
        <input type="hidden" name="application_id" value="<?php echo (int)$id; ?>" />

        <label for="remarks">Remarks <small>(optional notes)</small></label>
        <textarea id="remarks" name="remarks" rows="4"
                  placeholder="Enter any remarks or notes here..."></textarea>

        <div class="form-actions" style="margin-top:18px;">
            <button type="submit" name="action" value="approve" class="btn btn-success">
                ✔ Approve &amp; Issue Certificate
            </button>
            <button type="submit" name="action" value="reject" class="btn btn-danger"
                    style="margin-left:12px;">
                ✘ Reject
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

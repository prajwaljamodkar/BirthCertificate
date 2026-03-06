<?php
/**
 * authority1/approve.php — Handles approve/reject action for Authority 1.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

requireRole('authority1');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'authority1/dashboard.php');
    exit;
}

$id      = (int)($_POST['application_id'] ?? 0);
$action  = trim($_POST['action'] ?? '');
$remarks = trim($_POST['remarks'] ?? '');

if ($id <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    header('Location: ' . BASE_URL . 'authority1/dashboard.php');
    exit;
}

$pdo = getDB();

// Verify the application exists and is still pending
$check = $pdo->prepare("SELECT id FROM applications WHERE id = :id AND status = 'pending'");
$check->execute([':id' => $id]);
if (!$check->fetch()) {
    header('Location: ' . BASE_URL . 'authority1/dashboard.php');
    exit;
}

$newStatus = ($action === 'approve') ? 'approved_auth1' : 'rejected_auth1';

$stmt = $pdo->prepare(
    'UPDATE applications
        SET status = :st, auth1_remarks = :rm, auth1_action_at = NOW()
      WHERE id = :id'
);
$stmt->execute([
    ':st' => $newStatus,
    ':rm' => $remarks,
    ':id' => $id,
]);

header('Location: ' . BASE_URL . 'authority1/dashboard.php?msg=' . urlencode(
    $action === 'approve'
        ? 'Application #' . $id . ' approved and forwarded to Authority 2.'
        : 'Application #' . $id . ' rejected.'
));
exit;

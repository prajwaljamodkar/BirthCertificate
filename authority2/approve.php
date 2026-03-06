<?php
/**
 * authority2/approve.php — Handles approve/reject action for Authority 2.
 * Approving sets the status to 'verified', enabling the user to download the certificate.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';

requireRole('authority2');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'authority2/dashboard.php');
    exit;
}

$id      = (int)($_POST['application_id'] ?? 0);
$action  = trim($_POST['action'] ?? '');
$remarks = trim($_POST['remarks'] ?? '');

if ($id <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    header('Location: ' . BASE_URL . 'authority2/dashboard.php');
    exit;
}

$pdo = getDB();

// Verify the application exists and is approved by auth1
$check = $pdo->prepare("SELECT id FROM applications WHERE id = :id AND status = 'approved_auth1'");
$check->execute([':id' => $id]);
if (!$check->fetch()) {
    header('Location: ' . BASE_URL . 'authority2/dashboard.php');
    exit;
}

// Approving by Auth2 moves status to 'verified' (fully approved); rejecting to 'rejected_auth2'
$newStatus = ($action === 'approve') ? 'verified' : 'rejected_auth2';

$stmt = $pdo->prepare(
    'UPDATE applications
        SET status = :st, auth2_remarks = :rm, auth2_action_at = NOW()
      WHERE id = :id'
);
$stmt->execute([
    ':st' => $newStatus,
    ':rm' => $remarks,
    ':id' => $id,
]);

header('Location: ' . BASE_URL . 'authority2/dashboard.php?msg=' . urlencode(
    $action === 'approve'
        ? 'Application #' . $id . ' verified. The user can now download the certificate.'
        : 'Application #' . $id . ' rejected.'
));
exit;

<?php
/**
 * user/download.php — Generates and downloads the verified birth certificate PDF.
 * Only works when the application status is 'verified'.
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../fpdf.php';

requireRole('user');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ' . BASE_URL . 'user/dashboard.php');
    exit;
}

$pdo  = getDB();
$stmt = $pdo->prepare(
    'SELECT a.*, u.full_name AS applicant_name
       FROM applications a
       JOIN users u ON u.id = a.user_id
      WHERE a.id = :id AND a.user_id = :uid'
);
$stmt->execute([':id' => $id, ':uid' => $_SESSION['user_id']]);
$app = $stmt->fetch();

if (!$app) {
    header('Location: ' . BASE_URL . 'user/dashboard.php');
    exit;
}

if ($app['status'] !== 'verified') {
    // Not yet verified — redirect back
    header('Location: ' . BASE_URL . 'user/dashboard.php');
    exit;
}

// ---- Generate PDF ----

class BirthCertificatePDF extends FPDF {
    public function Header() {
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(26, 115, 232);
        $this->Cell(0, 12, 'GOVERNMENT BIRTH CERTIFICATE', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, 'Issued by the Birth Certificate Authority', 0, 1, 'C');
        $this->Ln(4);
        $this->SetDrawColor(26, 115, 232);
        $this->SetLineWidth(0.8);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(6);
        $this->SetTextColor(0, 0, 0);
    }

    public function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 5, 'This certificate is computer-generated and digitally verified.', 0, 1, 'C');
        $this->Cell(0, 5, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    public function SectionTitle(string $title): void {
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(230, 240, 255);
        $this->SetTextColor(26, 115, 232);
        $this->Cell(0, 8, '  ' . $title, 0, 1, 'L', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(2);
    }

    public function DetailRow(string $label, string $value): void {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 8, $label . ':', 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 8, $value, 0, 1);
    }

    public function VerifiedStamp(): void {
        // Semi-transparent green box in the corner
        $this->SetFont('Arial', 'B', 28);
        $this->SetTextColor(52, 168, 83);
        $this->SetXY(130, 220);
        $this->Cell(60, 14, 'VERIFIED', 1, 0, 'C');
        $this->SetTextColor(0, 0, 0);
    }
}

$pdf = new BirthCertificatePDF();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 20);

// Certificate number & date
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 6, 'Certificate No: BC-' . str_pad((string)$app['id'], 6, '0', STR_PAD_LEFT)
    . '          Date of Issue: ' . date('d-m-Y'), 0, 1, 'R');
$pdf->Ln(4);

// Personal details section
$pdf->SectionTitle('Personal Details');
$fullName = $app['fname'] . ' ' . ($app['mname'] ? $app['mname'] . ' ' : '') . $app['lname'];
$pdf->DetailRow('Full Name',    $fullName);
$pdf->DetailRow('Birth Date',   date('d-m-Y', strtotime($app['birthdate'])));
$pdf->DetailRow('Birth Place',  $app['bplace']);
$pdf->DetailRow('Gender',       $app['gender']);

$pdf->Ln(4);

// Family details
$pdf->SectionTitle('Family Details');
$pdf->DetailRow("Father's Name", $app['father_name']);
$pdf->DetailRow("Mother's Name", $app['mother_name']);

$pdf->Ln(4);

// Other details
$pdf->SectionTitle('Other Details');
$pdf->DetailRow('Religion', $app['religion']);
$pdf->DetailRow('Category', $app['category']);

$pdf->Ln(8);

// Approval information
$pdf->SectionTitle('Approval Information');
$pdf->SetFont('Arial', '', 10);
$auth1Date = $app['auth1_action_at'] ? date('d-m-Y H:i', strtotime($app['auth1_action_at'])) : '—';
$auth2Date = $app['auth2_action_at'] ? date('d-m-Y H:i', strtotime($app['auth2_action_at'])) : '—';
$pdf->DetailRow('Approved by Authority 1', $auth1Date);
$pdf->DetailRow('Approved by Authority 2', $auth2Date);

// Verified stamp
$pdf->VerifiedStamp();

// Signature lines
$pdf->Ln(16);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(65, 6, '', 'T', 0, 'C');
$pdf->Cell(10);
$pdf->Cell(65, 6, '', 'T', 0, 'C');
$pdf->Ln(4);
$pdf->Cell(65, 6, 'Authority 1 Signature', 0, 0, 'C');
$pdf->Cell(10);
$pdf->Cell(65, 6, 'Authority 2 Signature', 0, 1, 'C');

$filename = 'BirthCertificate_' . str_pad((string)$app['id'], 6, '0', STR_PAD_LEFT) . '.pdf';
$pdf->Output('D', $filename);
exit;

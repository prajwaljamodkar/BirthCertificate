<?php
require('./fpdf/fpdf.php');

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $birthdate = $_POST['birthdate'];
    $bplace = $_POST['bplace'];
    $gender = $_POST['gender'];
    $frname = $_POST['frname'];
    $mrname = $_POST['mrname'];
    $caste = $_POST['caste'];
    $category = $_POST['category'];

    $pdf = new FPDF();
    $pdf->AddPage();

    // Title
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(75);
    $pdf->Cell(50, 10, 'Birth Certificate', 1, 0, 'C');
    $pdf->Ln(20);

    // Name
    $pdf->SetFont('Arial', '', 15);
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Name:");
    $pdf->Cell(50, 10, $fname . ' ' . $mname . ' ' . $lname);
    $pdf->Ln(15);

    // Birth Date
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Birth Date (yyyy/mm/dd):");
    $pdf->Cell(50, 10, $birthdate);
    $pdf->Ln(15);

    // Birth Place
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Birth Place:");
    $pdf->Cell(50, 10, $bplace);
    $pdf->Ln(15);

    // Gender
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Gender:");
    $pdf->Cell(50, 10, $gender);
    $pdf->Ln(15);

    // Father's Name
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Father Name:");
    $pdf->Cell(50, 10, $frname);
    $pdf->Ln(15);

    // Mother's Name
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Mother Name:");
    $pdf->Cell(50, 10, $mrname);
    $pdf->Ln(15);

    // Religion
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Religion:");
    $pdf->Cell(50, 10, $caste);
    $pdf->Ln(15);

    // Category
    $pdf->Cell(10);
    $pdf->Cell(50, 10, "Category:");
    $pdf->Cell(50, 10, $category);
    $pdf->Ln(15);

    // Output the PDF
    $pdf->Output();
}
?>

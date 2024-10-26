<?php
// Include the FPDF library
require('./fpdf/fpdf.php');

// Create a new PDF instance
$pdf = new FPDF();

// Add a new page
$pdf->AddPage();

// Set font for the title
$pdf->SetFont('Arial', 'B', 16);

// Add a title
$pdf->Cell(0, 10, 'Hello, World!', 0, 1, 'C');

// Set font for the content
$pdf->SetFont('Arial', '', 12);

// Add some text content
$pdf->Cell(0, 10, 'This is a sample PDF created using FPDF in PHP.', 0, 1);

// Add another line of text
$pdf->Cell(0, 10, 'FPDF makes it easy to generate PDFs.', 0, 1);

// Output the PDF to the browser
$pdf->Output();
?>

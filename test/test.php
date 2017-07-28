<?php
require('fpdf/korean.php');

$pdf = new PDF_Korean();
$pdf->AddUHCFont();
$pdf -> AddPage();
$pdf -> SetFont("UHC", "B", 16);
$pdf -> Cell(40, 10, "외출 • 외박 • 결석");
$pdf -> Output();

?>
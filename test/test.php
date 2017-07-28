<?php
require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf -> AddPage();
$pdf -> SetFont("Arial", "B", 16);
$pdf -> Cell(40, 10, "외출 • 외박 • 결석");
$pdf -> Output();

?>
<?php
require('korean.php');

$pdf = new PDF_Korean();
$pdf->AddUHCFont();
$pdf->AddPage();
$pdf->SetFont('UHC','',18);
$pdf->Write(8,'PHP 3.0은 1998년 6월에 공식적으로 릴리즈되었다. 공개적인 테스트 이후약 9개월만이었다.');
$pdf->Output();
?>

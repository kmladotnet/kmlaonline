<?php
require('korean.php');

$pdf = new PDF_Korean();
$pdf->AddUHCFont();
$pdf->AddPage();
$pdf->SetFont('UHC','',18);
$pdf->Write(8,'PHP 3.0�� 1998�� 6���� ���������� ������Ǿ���. �������� �׽�Ʈ ���ľ� 9�������̾���.');
$pdf->Output();
?>

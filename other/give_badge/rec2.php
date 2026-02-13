<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php'; // Include Composer autoloader
use setasign\Fpdi\Fpdi;

$pdf = new Fpdi();
$pdf->AddPage();

$pdf->setSourceFile('gift_coupon.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

$pdf->SetFont('Arial', '', 13);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(20, 20);
$pdf->Write(0, 'gift code');
$pdf->Output('gift_coupon_generated.pdf', 'D');

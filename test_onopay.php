<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$s = new \App\Services\OnopayService();

// Generate QR first
$gen = $s->generateQR('08123456789', 35000, 'MARTIP', 'TEST DESC');
print_r($gen);

if ($gen['success']) {
    $qrCode = $gen['data']['qr_code'];
    echo "QR Code: $qrCode\n";
    
    // Pay using generated QR
    $pay = $s->pay($qrCode, '089690260334');
    print_r($pay);
}

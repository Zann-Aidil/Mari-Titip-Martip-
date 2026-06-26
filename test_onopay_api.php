<?php
// Test koneksi ke Onopay API
$url = 'https://onopay.web.id/api/v1/payment/qr/generate';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'phone_number' => '08123456789',
    'amount'       => 1000,
    'qr_mode'      => 'single_use',
    'merchant_code'=> 'TEST'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result   = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

echo "HTTP Code : " . $httpCode . PHP_EOL;
echo "cURL Error: " . ($error ?: 'none') . PHP_EOL;
echo "Response  : " . $result . PHP_EOL;

$decoded = json_decode($result, true);
echo PHP_EOL . "=== PARSED ===" . PHP_EOL;
print_r($decoded);

<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$locations = App\Models\Location::all();
foreach ($locations as $l) {
    echo $l->id . ' | ' . $l->nama_lokasi . ' | [' . $l->image . ']' . PHP_EOL;
    echo '  -> asset: ' . asset('storage/' . $l->image) . PHP_EOL;
    echo '  -> Storage::url: ' . Illuminate\Support\Facades\Storage::url($l->image) . PHP_EOL;
}

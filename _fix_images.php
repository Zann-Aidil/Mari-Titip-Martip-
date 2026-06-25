<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Fix Stasiun Mini (empty string -> null)
$updated = App\Models\Location::where('image', '')->update(['image' => null]);
echo "Fixed $updated locations with empty image string" . PHP_EOL;

// Verify
foreach (App\Models\Location::all() as $l) {
    echo $l->id . ' | ' . $l->nama_lokasi . ' | image=' . ($l->image ?? 'NULL') . PHP_EOL;
}

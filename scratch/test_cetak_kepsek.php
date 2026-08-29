<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('role', 'guru_piket')->first() ?? \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$c = app(\App\Http\Controllers\GuruPiketDashboardController::class);
$req = request();

$html = $c->cetakLaporan($req)->render();

// Check if Trisno Wibowo is in the rendered HTML
if (strpos($html, 'Trisno Wibowo') !== false) {
    echo "SUCCESS: 'Trisno Wibowo' found in rendered Cetak Laporan HTML!\n";
} else {
    echo "WARNING: 'Trisno Wibowo' not found!\n";
}

// Print signature section from HTML
preg_match('/<div class="signatures">.*?<\/div>\s*<\/div>/s', $html, $matches);
if (!empty($matches)) {
    echo "\nRendered Signature Section:\n";
    echo strip_tags($matches[0]);
    echo "\n";
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- KepalaSekolah table ---\n";
print_r(\App\Models\KepalaSekolah::all()->toArray());

echo "--- Users with role kepala_sekolah ---\n";
print_r(\App\Models\User::where('role', 'kepala_sekolah')->get()->toArray());

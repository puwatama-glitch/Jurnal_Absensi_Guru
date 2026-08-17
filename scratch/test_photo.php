<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Storage;

try {
    $user = User::first();
    echo "Current user: " . $user->name . "\n";
    $user->photo = 'profile-photos/test_script.jpg';
    $user->save();
    echo "Saved user photo: " . $user->photo . "\n";

    $written = Storage::disk('public')->put('profile-photos/test_script.jpg', 'fake image bytes');
    echo "Storage disk public write result: " . ($written ? "SUCCESS" : "FAILED") . "\n";
    echo "Storage URL: " . Storage::url('profile-photos/test_script.jpg') . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

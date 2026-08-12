<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurnalMengajarController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('jurnal', JurnalMengajarController::class);
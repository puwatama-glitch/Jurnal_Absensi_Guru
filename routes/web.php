<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JurnalMengajarController;

Route::get('/', function () {
    return redirect('/jurnal');
});

Route::resource('jurnal', JurnalMengajarController::class);
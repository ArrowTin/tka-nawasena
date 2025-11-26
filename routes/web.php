<?php

use App\Http\Controllers\Tka\UjianController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('tka', [UjianController::class, 'index'])->name('tka.index');
Route::get('template', function () {
    return view('template');
})->name('template');




Route::prefix('su')->group(function(){
    Route::view('/education','master.education_levels.index');
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\PageController::class, 'welcome'])->name('welcome');
Route::post('/get-airport', [\App\Http\Controllers\NemoController::class, 'airport'])->name('airport');

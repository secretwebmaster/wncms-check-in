<?php

use Illuminate\Support\Facades\Route;
use Wncms\CheckIn\Controllers\CheckInController;

Route::middleware(['auth'])->group(function () {
    Route::get('check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('check-in', [CheckInController::class, 'store'])->name('check-in.store');
});

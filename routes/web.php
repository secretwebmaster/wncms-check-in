<?php

use Illuminate\Support\Facades\Route;
use Wncms\CheckIn\Controllers\CheckInController;

Route::name('frontend.')->middleware('web', 'is_installed', 'has_website')->group(function () {
    Route::prefix('check-ins')->controller(CheckInController::class)->group(function () {
        Route::get('/', 'index')->name('check_ins.index');
        Route::post('/', 'store')->name('check_ins.store');
    });
});

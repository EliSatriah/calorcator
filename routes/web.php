<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::post('/food/analyze', [\App\Http\Controllers\DashboardController::class, 'analyzeFood'])->name('food.analyze');

Route::middleware('auth')->group(function () {
    Route::post('/bio', [\App\Http\Controllers\DashboardController::class, 'storeBio'])->name('bio.store');
    Route::get('/history', [\App\Http\Controllers\DashboardController::class, 'history'])->name('history.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

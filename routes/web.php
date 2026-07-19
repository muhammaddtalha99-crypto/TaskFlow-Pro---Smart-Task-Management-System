<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
});

require __DIR__.'/auth.php';
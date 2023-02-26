<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RolesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function (){
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('roles', RolesController::class);
});

require __DIR__.'/auth.php';

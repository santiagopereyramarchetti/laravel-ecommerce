<?php

use App\Http\Controllers\Admin\AttachPermisionToRoleController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DettachPermisionFromRoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function (){
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::post('roles/attach-permission', AttachPermisionToRoleController::class)->name('roles.attach-permission');
    Route::post('roles/dettach-permission', DettachPermisionFromRoleController::class)->name('roles.detach-permission');
    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UsersController::class);
    Route::resource('categories', CategoriesController::class);
});

require __DIR__.'/auth.php';

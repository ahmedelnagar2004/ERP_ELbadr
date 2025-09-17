<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:view-dashboard'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes - Protected by permissions
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // User Management Routes
    Route::middleware(['permission:view-users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Role Management Routes
    Route::middleware(['permission:manage-roles'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Items Management
    Route::middleware(['permission:view-items'])->group(function () {
        Route::resource('items', ItemController::class);
    });

    // Categories Management  
    Route::middleware(['permission:view-categories'])->group(function () {
        Route::resource('categories', CategoryController::class);
    });

    // Units Management
    Route::middleware(['permission:view-units'])->group(function () {
        Route::resource('units', UnitController::class);
    });

    // Clients management routes
    Route::middleware(['permission:manage-clients'])->group(function () {
        Route::resource('clients', ClientController::class);
    });

    // Orders management routes
    Route::middleware(['permission:manage-orders'])->group(function () {
        Route::resource('orders', OrderController::class);
    });

    // Settings routes
    Route::middleware(['permission:manage-settings'])->group(function () {
        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('settings.index');
    });

    // Inventory routes
    Route::middleware(['permission:view-inventory'])->group(function () {
        Route::get('/inventory', function () {
            return view('admin.inventory.index');
        })->name('inventory.index');
    });

    // Example protected routes for different ERP modules
    Route::middleware('permission:view-clients')->group(function () {
        Route::get('/clients', function () {
            return view('admin.clients.index');
        })->name('clients.index');
    });
    
    Route::middleware('permission:view-orders')->group(function () {
        Route::get('/orders', function () {
            return view('admin.orders.index');
        })->name('orders.index');
    });
    
    Route::middleware('permission:view-sales')->group(function () {
        Route::get('/sales', function () {
            return view('admin.sales.index');
        })->name('sales.index');
    });
    
    Route::middleware('permission:view-reports')->group(function () {
        Route::get('/reports', function () {
            return view('admin.reports.index');
        })->name('reports.index');
    });
});

// Remove the duplicate routes at the bottom
require __DIR__.'/auth.php';



























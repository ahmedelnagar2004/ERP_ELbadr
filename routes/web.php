<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//route for notification
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
// Language switcher route
Route::post('lang/{locale}', [LanguageController::class, 'switchLang'])
    ->name('lang.switch');

// Apply web middleware group to all routes
Route::middleware(['web'])->group(function () {
    // Apply set.locale middleware to routes that need it
    Route::middleware(['set.locale'])->group(function () {
        // Public routes
        Route::get('/', function () {
            return view('welcome');
        })->name('home');

        // Authenticated routes
        Route::middleware(['auth', 'verified'])->group(function () {
            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->middleware('permission:view-dashboard')
                ->name('dashboard');

            // Profile
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            // Admin Routes
            Route::prefix('admin')->name('admin.')->group(function () {
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
                Route::middleware(['permission:view-clients'])->group(function () {
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

                // Safes Management
                Route::middleware(['permission:view-safes'])->group(function () {
                    Route::resource('safes', \App\Http\Controllers\SafeController::class)->except(['show']);
                    
                    // Additional safe routes can be added here
                    // Example: Route::get('safes/{safe}/transactions', [\App\Http\Controllers\SafeController::class, 'transactions'])->name('safes.transactions');
                });
             // Inventory routes
                Route::middleware(['permission:view-inventory'])->group(function () {
                    Route::get('/inventory', function () {
                        return view('admin.inventory.index');
                    })->name('inventory.index');
                });

                // TEST ROUTE - Remove after testing
                Route::get('/test', function () {
                    return 'Route test successful';
                })->name('test');

                // Example protected routes for different ERP modules
                Route::middleware('permission:view-orders')->group(function () {
                    Route::get('/orders', function () {
                        return view('admin.orders.index');
                    })->name('orders.index');
                });

                // Sales management routes
                Route::middleware(['permission:view-sales'])->group(function () {
                    Route::get('/sales', [\App\Http\Controllers\SaleController::class, 'index'])->name('sales.index');
                    Route::get('/sales/create', [\App\Http\Controllers\SaleController::class, 'create'])->name('sales.create');
                    Route::post('/sales', [\App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
                    Route::get('/sales/{id}', [\App\Http\Controllers\SaleController::class, 'show'])->name('sales.show');
                    Route::get('/sales/{id}/edit', [\App\Http\Controllers\SaleController::class, 'edit'])->name('sales.edit');
                    Route::put('/sales/{id}', [\App\Http\Controllers\SaleController::class, 'update'])->name('sales.update');
                    Route::delete('/sales/{id}', [\App\Http\Controllers\SaleController::class, 'destroy'])->name('sales.destroy');
                    Route::get('/sales/{id}/print', [\App\Http\Controllers\SaleController::class, 'print'])->name('sales.print');
                    Route::post('/sales/{id}/complete', [\App\Http\Controllers\SaleController::class, 'complete'])->name('sales.complete');
                });

                // Reports
                Route::middleware('permission:view-reports')->group(function () {
                    Route::get('/reports', function () {
                        return view('admin.reports.index');
                    })->name('reports.index');
                });
            }); // End of admin prefix group
        }); // End of auth middleware group
    }); // End of set.locale middleware group
}); // End of web middleware group

// Authentication routes
require __DIR__.'/auth.php';
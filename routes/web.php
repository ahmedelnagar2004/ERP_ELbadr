<?php

use App\Http\Controllers\AlertQuantityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayRemainingController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

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
// route for notfication
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
                // payremaining routes
                Route::middleware(['permission:view-payremaining'])->group(function () {
                    Route::resource('payremaining', PayRemainingController::class);
                });

                

                // Role Management Routes
                Route::middleware(['permission:manage-roles'])->group(function () {
                    Route::resource('roles', RoleController::class);
                });

                // Alerts Management
                Route::middleware(['permission:alert-quantity'])->group(function () {
                    Route::get('alerts', [AlertQuantityController::class, 'index'])->name('alerts.index');
                });

                // Items Management
                Route::middleware(['permission:view-items'])->group(function () {
                    Route::resource('items', ItemController::class);
                    Route::delete('items/photo/{photoId}', [ItemController::class, 'deletePhoto'])->name('items.photo.delete');
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
                Route::middleware(['permission:view-orders'])->group(function () {
                    Route::resource('orders', OrderController::class);
                });
                // warehouses management routes
                Route::middleware(['permission:view-warehouses'])->group(function () {
                    Route::resource('warehouses', WarehouseController::class);
                    Route::get('warehouses/{warehouse}/items', [WarehouseController::class, 'warehouseItems'])->name('warehouses.items');
                });

                // Settings routes
                Route::middleware(['permission:manage-settings'])->group(function () {
                    Route::get('settings', [GeneralSettingsController::class, 'edit'])->name('settings.edit');
                    Route::put('settings', [GeneralSettingsController::class, 'update'])->name('settings.update');
                });

                // Safes Management
                Route::middleware(['permission:view-safes'])->group(function () {
                    Route::resource('safes', \App\Http\Controllers\SafeController::class);

                    // Additional safe routes can be added here
                    // Example: Route::get('safes/{safe}/transactions', [\App\Http\Controllers\SafeController::class, 'transactions'])->name('safes.transactions');
                });

                // Inventory routes
                Route::middleware(['permission:view-inventory'])->group(function () {
                    Route::get('/inventory', function () {
                        return view('admin.inventory.index');
                    })->name('inventory.index');
                });

                // Cart management routes
                Route::middleware(['permission:view-cart'])->group(function () {
                    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
                    Route::get('/cart/{id}', [\App\Http\Controllers\CartController::class, 'show'])->name('cart.show');
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
                    Route::get('/sales/{id}/complete', [\App\Http\Controllers\SaleController::class, 'complete'])->name('sales.complete');
                    
                    // Sales Returns
                    Route::get('/returns/create', [\App\Http\Controllers\SaleReturnController::class, 'create'])->name('returns.create');
                    Route::post('/returns', [\App\Http\Controllers\SaleReturnController::class, 'store'])->name('returns.store');
                });

                // Reports
                Route::middleware('permission:view-reports')->group(function () {
                    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
                    Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
                    Route::get('/reports/clients', [\App\Http\Controllers\ReportController::class, 'clients'])->name('reports.clients');
                    Route::get('/reports/item-transactions', [\App\Http\Controllers\ReportController::class, 'itemTransactions'])->name('reports.item_transactions');
                    Route::get('/reports/products', [\App\Http\Controllers\ReportController::class, 'products'])->name('reports.products');
                });
            }); // End of admin prefix group
        }); // End of auth middleware group
    }); // End of set.locale middleware group
}); // End of web middleware group

// Authentication routes
require __DIR__.'/auth.php';
require __DIR__.'/api.php';

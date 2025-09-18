<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StationaryItemController;
use App\Http\Controllers\GodownController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StationaryAwardController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiteSettingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management Routes (Super Admin Only)
Route::middleware(['custom.permission:users.view'])->group(function () {
    Route::resource('users', UserController::class);
});

// Site Settings Routes (Super Admin Only)
Route::middleware(['custom.permission:site-settings.view'])->group(function () {
    Route::get('/site-settings', [SiteSettingController::class, 'index'])->name('site-settings.index');
    Route::post('/site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');
    Route::post('/site-settings/upload-image', [SiteSettingController::class, 'uploadImage'])->name('site-settings.upload-image');
    Route::delete('/site-settings/remove-image', [SiteSettingController::class, 'removeImage'])->name('site-settings.remove-image');
    Route::post('/site-settings/reset', [SiteSettingController::class, 'reset'])->name('site-settings.reset');
});

    // Dashboard Routes
    Route::middleware(['custom.permission:dashboard.view'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stock-report', [DashboardController::class, 'stockReport'])->name('dashboard.stock-report');
        Route::get('/dashboard/sales-report', [DashboardController::class, 'salesReport'])->name('dashboard.sales-report');
        Route::get('/dashboard/awards-report', [DashboardController::class, 'awardsReport'])->name('dashboard.awards-report');
    });

    // Stationary Items Routes
    Route::middleware(['custom.permission:stationary-items.view'])->group(function () {
        Route::resource('stationary-items', StationaryItemController::class);
        Route::get('/stationary-items/{stationaryItem}/stock', [StationaryItemController::class, 'stock'])->name('stationary-items.stock');
    });

    // Godowns Routes
    Route::middleware(['custom.permission:godowns.view'])->group(function () {
        Route::resource('godowns', GodownController::class);
        Route::get('/godowns/{godown}/stock', [GodownController::class, 'stock'])->name('godowns.stock');
        Route::get('/godowns/{godown}/movements', [GodownController::class, 'movements'])->name('godowns.movements');
    });

    // Customers Routes
    Route::middleware(['custom.permission:customers.view'])->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::get('/customers/{customer}/purchases', [CustomerController::class, 'purchases'])->name('customers.purchases');
        Route::get('/customers/{customer}/donations', [CustomerController::class, 'donations'])->name('customers.donations');
    });

    // Schools Routes
    Route::middleware(['custom.permission:schools.view'])->group(function () {
        Route::resource('schools', SchoolController::class);
        Route::get('/schools/{school}/students', [SchoolController::class, 'students'])->name('schools.students');
        Route::get('/schools/{school}/awards', [SchoolController::class, 'awards'])->name('schools.awards');
    });

    // Students Routes
    Route::middleware(['custom.permission:students.view'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::get('/students/{student}/awards', [StudentController::class, 'awards'])->name('students.awards');
        Route::post('/students/{student}/dropout', [StudentController::class, 'markDropout'])->name('students.dropout');
    });

    // Sales Routes
    Route::middleware(['custom.permission:sales.view'])->group(function () {
        Route::resource('sales', SaleController::class);
        Route::get('/sales/create/invoice', [SaleController::class, 'createInvoice'])->name('sales.create-invoice');
        Route::post('/sales/generate-invoice', [SaleController::class, 'generateInvoice'])->name('sales.generate-invoice');
        Route::get('/sales/{sale}/invoice', [SaleController::class, 'showInvoice'])->name('sales.show-invoice');
    });

    // Stationary Awards Routes
    Route::middleware(['custom.permission:stationary-awards.view'])->group(function () {
        Route::resource('stationary-awards', StationaryAwardController::class);
        Route::post('/stationary-awards/{stationaryAward}/return', [StationaryAwardController::class, 'return'])->name('stationary-awards.return');
        Route::get('/stationary-awards/bulk/create', [StationaryAwardController::class, 'bulkCreate'])->name('stationary-awards.bulk-create');
        Route::post('/stationary-awards/bulk/store', [StationaryAwardController::class, 'bulkStore'])->name('stationary-awards.bulk-store');
    });

    // Procurement Routes
    Route::middleware(['custom.permission:procurements.view'])->group(function () {
        Route::resource('procurements', ProcurementController::class);
        Route::post('/procurements/{procurement}/receive', [ProcurementController::class, 'receive'])->name('procurements.receive');
    });

    // Stock Movement Routes
    Route::middleware(['custom.permission:stock-movements.view'])->group(function () {
        Route::resource('stock-movements', StockMovementController::class);
        Route::post('/stock-movements/{stockMovement}/complete', [StockMovementController::class, 'complete'])->name('stock-movements.complete');
        Route::get('/stock-movements/adjustment/create', [StockMovementController::class, 'adjustment'])->name('stock-movements.adjustment');
        Route::post('/stock-movements/adjustment/store', [StockMovementController::class, 'storeAdjustment'])->name('stock-movements.adjustment.store');
    });

    // Stock Management Routes
    Route::middleware(['custom.permission:stock-movements.view'])->prefix('stock-management')->name('stock-management.')->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::get('/adjustment', [StockMovementController::class, 'adjustment'])->name('adjustment');
        Route::post('/adjustment', [StockMovementController::class, 'storeAdjustment'])->name('adjustment.store');
        Route::get('/movements', [StockMovementController::class, 'index'])->name('movements');
        Route::get('/movements/create', [StockMovementController::class, 'create'])->name('movements.create');
        Route::post('/movements', [StockMovementController::class, 'store'])->name('movements.store');
    });

    // Reports Routes
    Route::middleware(['custom.permission:reports.view'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/stock', [DashboardController::class, 'stockReport'])->name('stock');
        Route::get('/sales', [DashboardController::class, 'salesReport'])->name('sales');
        Route::get('/awards', [DashboardController::class, 'awardsReport'])->name('awards');
        Route::get('/customers', [CustomerController::class, 'report'])->name('customers');
        Route::get('/schools', [SchoolController::class, 'report'])->name('schools');
        Route::get('/students', [StudentController::class, 'report'])->name('students');
        Route::get('/procurements', [ProcurementController::class, 'report'])->name('procurements');
        Route::get('/expiry', [StationaryItemController::class, 'expiryReport'])->name('expiry');
        Route::get('/low-stock', [StationaryItemController::class, 'lowStockReport'])->name('low-stock');
    });
});

require __DIR__.'/auth.php';

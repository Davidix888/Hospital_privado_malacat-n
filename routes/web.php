<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::prefix('usuarios')
        ->middleware('can:usuarios.manage')
        ->name('usuarios.')
        ->controller(UserManagementController::class)
        ->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::get('/crear', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/listado', 'list')->name('list');
            Route::get('/{user}/editar', 'edit')->name('edit');
            Route::put('/{user}', 'update')->name('update');
            Route::get('/desactivar', 'deactivateIndex')->name('deactivate.index');
            Route::patch('/{user}/desactivar', 'deactivate')->name('deactivate');
            Route::patch('/{user}/activar', 'reactivate')->name('reactivate');
        });
    Route::view('/laboratorio', 'laboratorio.index')
        ->middleware('can:laboratorio.view')
        ->name('laboratorio.index');
    Route::prefix('farmacia')
        ->middleware('can:farmacia.view')
        ->name('farmacia.')
        ->controller(PharmacyController::class)
        ->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::get('/compras/menu', 'purchaseMenu')->name('purchases.menu');
            Route::get('/medicamentos', 'medicines')->name('medicines.index');
            Route::get('/medicamentos/crear', 'createMedicine')->name('medicines.create');
            Route::post('/medicamentos', 'storeMedicine')->name('medicines.store');
            Route::get('/proveedores', 'suppliers')->name('suppliers.index');
            Route::get('/proveedores/crear', 'createSupplier')->name('suppliers.create');
            Route::post('/proveedores', 'storeSupplier')->name('suppliers.store');
            Route::get('/compras', 'purchases')->name('purchases.index');
            Route::get('/compras/crear', 'createPurchase')->name('purchases.create');
            Route::post('/compras', 'storePurchase')->name('purchases.store');
            Route::get('/ventas', 'sales')->name('sales.index');
            Route::get('/inventario', 'inventory')->name('inventory.index');
            Route::get('/reportes', 'reports')->name('reports.index');
        });
    Route::view('/resumenes', 'resumenes.index')
        ->middleware('can:resumenes.view')
        ->name('resumenes.index');
    Route::get('/resumenes/exportar', function () {
        return response()->json([
            'message' => 'Exportacion autorizada correctamente.',
        ]);
    })->middleware('can:resumenes.export')->name('resumenes.export');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

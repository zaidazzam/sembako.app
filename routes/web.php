<?php

use App\Enums\UserRole;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarungController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {
    return match (auth()->user()->role) {
        UserRole::ADMIN => redirect()->route('admin.dashboard'),
        UserRole::PETUGAS => redirect()->route('petugas.dashboard'),
        UserRole::WARUNG => redirect()->route('warung.dashboard'),
    };
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'admin'])
            ->name('dashboard');

        Route::resource('warungs', WarungController::class)
            ->except(['show']);

            Route::resource('categories', CategoryController::class)
    ->except(['show']);
            Route::resource('products', ProductController::class)
            ->except(['show']);
            Route::resource('stock-movements', StockMovementController::class)
    ->only(['index', 'create', 'store', 'destroy']);
    });

/*
|--------------------------------------------------------------------------
| Petugas
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'petugas'])
            ->name('dashboard');

                    Route::resource('warungs', WarungController::class)
            ->except(['show']);

            Route::resource('categories', CategoryController::class)
    ->except(['show']);
            Route::resource('products', ProductController::class)
            ->except(['show']);
            Route::resource('stock-movements', StockMovementController::class)
    ->only(['index', 'create', 'store', 'destroy']);
    });


/*
|--------------------------------------------------------------------------
| Warung
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:warung'])
    ->prefix('warung')
    ->name('warung.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'warung'])
            ->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| Orders / Kebutuhan
|--------------------------------------------------------------------------
|
| Digunakan oleh:
| - Admin
| - Petugas
|
*/

Route::middleware(['auth', 'role:admin,petugas'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Daftar Kebutuhan
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');


        /*
        |--------------------------------------------------------------------------
        | Rekap Kebutuhan
        |--------------------------------------------------------------------------
        |
        | Harus diletakkan sebelum /orders/{order}
        | agar "recap" tidak dianggap sebagai ID order.
        |
        */

        Route::get('/orders/recap', [OrderController::class, 'recap'])
            ->name('orders.recap');
        Route::get('/orders/recap/export', [OrderController::class, 'exportRecap'])
            ->name('orders.recap.export');

        Route::get('/orders/recap/pdf', [OrderController::class, 'exportRecapPdf'])
            ->name('orders.recap.pdf');

        /*
        |--------------------------------------------------------------------------
        | Tambah Kebutuhan
        |--------------------------------------------------------------------------
        */
       // UPDATE STATUS
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');
        Route::get('/orders/create', [OrderController::class, 'create'])
            ->name('orders.create');

        Route::post('/orders', [OrderController::class, 'store'])
            ->name('orders.store');


        /*
        |--------------------------------------------------------------------------
        | Edit Kebutuhan
        |--------------------------------------------------------------------------
        */

        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])
            ->name('orders.edit');

        Route::put('/orders/{order}', [OrderController::class, 'update'])
            ->name('orders.update');

        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])
            ->name('orders.destroy');
        /*
        |--------------------------------------------------------------------------
        | Detail Kebutuhan
        |--------------------------------------------------------------------------
        */

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

    });


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

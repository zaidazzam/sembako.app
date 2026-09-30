<?php

use App\Enums\UserRole;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
Route::get('/orders/recap', [OrderController::class, 'recap'])
    ->name('orders.recap');
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

        Route::get('/dashboard', function () {
            return view('petugas.dashboard');
        })->name('dashboard');

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

        Route::get('/dashboard', function () {
            return view('warung.dashboard');
        })->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| Orders / Kebutuhan
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,petugas'])
    ->group(function () {

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/create', [OrderController::class, 'create'])
            ->name('orders.create');

        Route::post('/orders', [OrderController::class, 'store'])
            ->name('orders.store');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');
    });

    Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

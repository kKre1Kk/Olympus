<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GodController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('gods.index');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('gods.index');
    })->name('dashboard');

    Route::resource('gods', GodController::class)
        ->withTrashed(['show']);

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('users.show');

    Route::get('/admin/gods/trashed', [GodController::class, 'trashed'])
        ->middleware('can:admin')
        ->name('gods.trashed');

    Route::post('/gods/{god}/restore', [GodController::class, 'restore'])
        ->withTrashed()
        ->middleware('can:restore-god,god')
        ->name('gods.restore');

    Route::delete('/gods/{god}/force', [GodController::class, 'forceDelete'])
        ->withTrashed()
        ->middleware('can:force-delete-god,god')
        ->name('gods.forceDelete');
});

require __DIR__.'/auth.php';

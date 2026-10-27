<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GodController;

Route::get('/', [GodController::class, 'index'])->name('gods.index');
Route::get('/gods/create', [GodController::class, 'create'])->name('gods.create');
Route::post('/gods', [GodController::class, 'store'])->name('gods.store');
Route::get('/gods/{god}/edit', [GodController::class, 'edit'])->name('gods.edit');
Route::put('/gods/{god}', [GodController::class, 'update'])->name('gods.update');
Route::delete('/gods/{god}', [GodController::class, 'destroy'])->name('gods.destroy');
Route::get('/gods/{god}', [GodController::class, 'show'])->name('gods.show');
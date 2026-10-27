<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GodController;

Route::get('/', [GodController::class, 'index']);
Route::resource('gods', GodController::class);
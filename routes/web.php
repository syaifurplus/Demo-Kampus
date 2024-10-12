<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;

Route::get('/dosen', [DosenController::class, 'index']);     // Rute untuk menampilkan semua dosen
Route::get('/dosen/{id}', [DosenController::class, 'show']); //
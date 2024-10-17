<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;

Route::get('/dosen', [DosenController::class, 'index']);     // Rute untuk menampilkan semua dosen
Route::get('/dosen/penelitian-pengabdian', [DosenController::class, 'penelitianPengabdian']);
Route::get('/dosen/perwalian', [DosenController::class, 'perwalian']);

Route::get('/dosen/bimbingan', [DosenController::class, 'bimbingan']);
Route::get('/dosen/aktivitas-perkuliahan', [DosenController::class, 'aktivitasPerkuliahan']);
Route::get('/dosen/aktivitas-penugasan', [DosenController::class, 'aktivitasPenugasan']);
Route::get('/dosen/{id}', [DosenController::class, 'show']); //
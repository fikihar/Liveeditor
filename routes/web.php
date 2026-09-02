<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\ClassController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\StudentController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// Auth routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Guru routes
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

    // Manajemen kelas
    Route::delete('kelas/destroy-all', [ClassController::class, 'destroyAll'])->name('kelas.destroyAll');
      Route::resource('kelas', ClassController::class)->except(['show']);
    Route::get('kelas/{kelas}', [ClassController::class, 'show'])->name('kelas.show');

    // Manajemen siswa per kelas
    Route::prefix('kelas/{kelas}/siswa')->name('siswa.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/tambah', [StudentController::class, 'create'])->name('create');
        Route::post('/', [StudentController::class, 'store'])->name('store');
        Route::get('/{siswa}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{siswa}', [StudentController::class, 'update'])->name('update');
        Route::delete('/destroy-all', [StudentController::class, 'destroyAll'])->name('destroyAll');
          Route::delete('/{siswa}', [StudentController::class, 'destroy'])->name('destroy');
        Route::post('/import', [StudentController::class, 'import'])->name('import');
    });
    // Manajemen Tugas/Latihan
        // Manajemen Tugas/Latihan
    Route::resource('tugas', \App\Http\Controllers\Guru\AssignmentController::class);
    Route::get('tugas/{tuga}/koreksi/{siswa}', [\App\Http\Controllers\Guru\AssignmentController::class, 'koreksi'])->name('tugas.koreksi');
    Route::post('tugas/{tuga}/koreksi/{siswa}', [\App\Http\Controllers\Guru\AssignmentController::class, 'simpanNilai'])->name('tugas.nilai');
    Route::post('tugas/{tuga}/force-submit', [\App\Http\Controllers\Guru\AssignmentController::class, 'forceSubmit'])->name('tugas.force_submit');
});

// Siswa routes
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat', [\App\Http\Controllers\Siswa\DashboardController::class, 'history'])->name('riwayat');
    
    // Live Editor
    Route::get('/tugas/{assignment}/editor', [\App\Http\Controllers\Siswa\EditorController::class, 'show'])->name('editor.show');
    Route::post('/tugas/{assignment}/log-cheat', [\App\Http\Controllers\Siswa\EditorController::class, 'logCheat'])->name('editor.cheat');
    Route::post('/tugas/{assignment}/submit', [\App\Http\Controllers\Siswa\EditorController::class, 'submit'])->name('editor.submit');
    Route::post('/tugas/{assignment}/log', [\App\Http\Controllers\Siswa\EditorController::class, 'logActivity'])->name('editor.log');
});
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
});
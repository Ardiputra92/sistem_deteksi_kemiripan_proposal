<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FingerprintPdfController;
use App\Http\Controllers\PlagiarismController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔐 Login & Logout Route (tanpa auth middleware)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// 🔁 Arahkan / ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 🛡 Semua route ini hanya bisa diakses jika sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/user', [HomeController::class, 'user'])->name('user.index');
    Route::put('/user/change-password', [UserController::class, 'changePassword'])->name('user.change-password');

    // Route PDF
    Route::get('/pdf', [FingerprintPdfController::class, 'index'])->name('pdf.index');
    Route::get('/pdf/create', [FingerprintPdfController::class, 'create'])->name('pdf.create');
    Route::post('/pdf', [FingerprintPdfController::class, 'store'])->name('pdf.store');
    Route::delete('/pdf/{id}', [FingerprintPdfController::class, 'destroy'])->name('pdf.destroy');
    Route::get('/pdf/{id}', [FingerprintPdfController::class, 'show'])->name('pdf.show');
    Route::get('/pdf/{id}/edit', [FingerprintPdfController::class, 'edit'])->name('pdf.edit');
    Route::put('/pdf/{id}', [FingerprintPdfController::class, 'update'])->name('pdf.update');
    Route::get('/result', [ResultController::class, 'index'])->name('result.index');
    Route::post('/result/update-threshold', [ResultController::class, 'updateThreshold'])->name('result.update-threshold');

    // Route Plagiarisme
    Route::get('/plagiarism', [PlagiarismController::class, 'index'])->name('plagiarism.index');
    Route::get('/plagiarism/create', [PlagiarismController::class, 'create'])->name('plagiarism.create');
    Route::post('/plagiarism/compare', [PlagiarismController::class, 'compare'])->name('plagiarism.compare');
    Route::delete('/plagiarism/{id}', [PlagiarismController::class, 'destroy'])->name('plagiarism.destroy');
    Route::get('/plagiarism/{id}/edit', [PlagiarismController::class, 'edit'])->name('plagiarism.edit');
    Route::put('/plagiarism/{id}', [PlagiarismController::class, 'update'])->name('plagiarism.update');

});

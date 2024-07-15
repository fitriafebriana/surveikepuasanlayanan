<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DasborController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\KuisionerController;
use App\Http\Controllers\LayananController;

use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RespondenController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/kuisioner', [IndexController::class, 'kuisioner'])->name('kuisioner');
Route::post('/result/store', [IndexController::class, 'store'])->name('result.store');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login'); 
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware(['web', 'auth'])->prefix('dasbor')->group(function () {
   Route::get('/', [DasborController::class, 'index'])->name('dasbor');
   Route::resource('/kuisioner', KuisionerController::class)->names('kuisioner');
   Route::post('/kuisioner/checks', [KuisionerController::class, 'checks'])->name('kuisioner.checks');
   Route::resource('/layanan', LayananController::class)->names('layanan');
   Route::post('/layanan/checks', [KuisionerController::class, 'checks'])->name('layanan.checks');
   Route::post('/layanan/export', [LayananController::class, 'export'])->name('layanan.export');
   Route::resource('/responden', RespondenController::class)->names('responden'); 
   Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
   Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
   Route::post('/auth/password', [AuthController::class, 'change_password'])->name('auth.change_password');
   
});

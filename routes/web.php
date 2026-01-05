<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UlasanController;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Public Katalog Kostum page
Route::get('/katalog_kostum', [HomeController::class, 'katalogKostum'])->name('katalog.kostum');
// Public Peraturan page
Route::get('/peraturan', [HomeController::class, 'peraturan'])->name('peraturan');
// Formulir Penyewaan Routes
Route::get('/formulir-penyewaan/{id_kostum}', [HomeController::class, 'formulirPenyewaan'])->name('formulir.penyewaan');
Route::post('/formulir-penyewaan/submit', [HomeController::class, 'submitFormulirPenyewaan'])->name('formulir.penyewaan.submit');
Route::get('/formulir-berhasil', [HomeController::class, 'formulirBerhasil'])->name('formulir.berhasil');

// User Profile Routes (Protected)
Route::get('/user/profile', [HomeController::class, 'userProfile'])->name('user.profile');
Route::post('/user/profile/update', [HomeController::class, 'updateUserProfile'])->name('user.profile.update');
Route::post('/user/profile/delete-photo', [HomeController::class, 'deleteProfilePhoto'])->name('user.profile.delete-photo');
Route::delete('/user/account/delete', [HomeController::class, 'deleteAccount'])->name('user.account.delete');

// Pesanan Saya Routes
Route::get('/pesanan-saya', [HomeController::class, 'pesananSaya'])->name('user.pesanan.saya');
Route::get('/pesanan-saya', [HomeController::class, 'pesananSaya'])->name('user.pesanan');
Route::get('/pesanan-saya/{id}/edit', [HomeController::class, 'editPesanan'])->name('user.pesanan.edit');
Route::post('/pesanan-saya/{id}/update', [HomeController::class, 'updatePesanan'])->name('user.pesanan.update');
Route::post('/pesanan-saya/{id}/cancel', [HomeController::class, 'cancelPesanan'])->name('user.pesanan.cancel');
Route::delete('/pesanan-saya/{id}/delete', [HomeController::class, 'deletePesanan'])->name('user.pesanan.delete');

// Auth Routes (Unified Login/Register for Admin & User)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Admin Routes (Keep old routes for backward compatibility)
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');
// Dashboard entry (named route expected by controllers)
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin stats endpoint (AJAX) for dashboard charts
Route::get('/admin/stats', [AdminController::class, 'stats'])->name('admin.stats');

// Data Katalog Routes
Route::get('/admin/data-katalog', [AdminController::class, 'dataKatalog'])->name('admin.data-katalog');
Route::post('/admin/katalog/store', [AdminController::class, 'storeKatalog'])->name('admin.katalog.store');
Route::post('/admin/katalog/update', [AdminController::class, 'updateKatalog'])->name('admin.katalog.update');
Route::post('/admin/katalog/delete/{id}', [AdminController::class, 'deleteKatalog'])->name('admin.katalog.delete');

// Data Kostum Routes
Route::get('/admin/data-kostum', [AdminController::class, 'dataKostum'])->name('admin.data-kostum');
Route::post('/admin/kostum/store', [AdminController::class, 'storeKostum'])->name('admin.kostum.store');
Route::post('/admin/kostum/update', [AdminController::class, 'updateKostum'])->name('admin.kostum.update');
Route::post('/admin/kostum/delete/{id}', [AdminController::class, 'deleteKostum'])->name('admin.kostum.delete');
Route::delete('/admin/kostum/image/{imageId}', [AdminController::class, 'deleteKostumImage'])->name('admin.kostum.image.delete');

// Profile Contact Routes
Route::get('/admin/profile-contact', [AdminController::class, 'profileContact'])->name('admin.profile-contact');
Route::post('/admin/profile-contact/update', [AdminController::class, 'updateProfileContact'])->name('admin.profile-contact.update');
Route::post('/admin/profile-contact/update-photo', [AdminController::class, 'updateProfileContactPhoto'])->name('admin.profile-contact.update-photo');
Route::post('/admin/profile-contact/delete-photo', [AdminController::class, 'deleteProfileContactPhoto'])->name('admin.profile-contact.delete-photo');
Route::post('/admin/profile-contact/update-qris', [AdminController::class, 'updatePaymentQris'])->name('admin.profile-contact.update-qris');
Route::post('/admin/profile-contact/delete-qris', [AdminController::class, 'deletePaymentQris'])->name('admin.profile-contact.delete-qris');

// Data Aturan Routes
Route::get('/admin/data-aturan', [AdminController::class, 'dataAturan'])->name('admin.data-aturan');
Route::post('/admin/aturan/store', [AdminController::class, 'storeAturan'])->name('admin.aturan.store');
Route::post('/admin/aturan/update', [AdminController::class, 'updateAturan'])->name('admin.aturan.update');
Route::post('/admin/aturan/delete/{id}', [AdminController::class, 'deleteAturan'])->name('admin.aturan.delete');

// Data Pesanan Routes
Route::get('/admin/data-pesanan', [AdminController::class, 'dataPesanan'])->name('admin.data-pesanan');
// Data Denda & Kerusakan Routes
Route::get('/admin/data-denda', [AdminController::class, 'dataDenda'])->name('admin.data-denda');
// CRUD for denda (store/update/destroy used by embedded UI on /admin/data-denda)
Route::post('/admin/denda/store', [\App\Http\Controllers\DendaController::class, 'store'])->name('admin.denda.store');
Route::post('/admin/denda/{id}/update', [\App\Http\Controllers\DendaController::class, 'update'])->name('admin.denda.update');
Route::post('/admin/denda/{id}/delete', [\App\Http\Controllers\DendaController::class, 'destroy'])->name('admin.denda.destroy');
// User-facing Denda page
Route::get('/denda-saya', [\App\Http\Controllers\DendaController::class, 'userIndex'])->name('user.denda-saya');
// Halaman bayar denda untuk user
Route::get('/bayar-denda/{id}', [\App\Http\Controllers\DendaController::class, 'showPayment'])->name('denda.bayar');
Route::post('/bayar-denda/{id}/upload', [\App\Http\Controllers\DendaController::class, 'storePayment'])->name('denda.bayar.upload');
Route::post('/admin/pesanan/update-status/{id}', [AdminController::class, 'updatePesananStatus'])->name('admin.pesanan.update-status');
// Admin delete pesanan (DELETE)
Route::delete('/admin/pesanan/{id}/delete', [AdminController::class, 'deletePesanan'])->name('admin.pesanan.delete');

// Data Pengguna (Users) Routes
Route::get('/admin/data-pengguna', [AdminController::class, 'dataPengguna'])->name('admin.data-pengguna');
Route::post('/admin/pengguna/update', [AdminController::class, 'updatePengguna'])->name('admin.pengguna.update');
Route::post('/admin/pengguna/delete/{id}', [AdminController::class, 'deletePengguna'])->name('admin.pengguna.delete');

// Data Ulasan Routes
Route::get('/admin/data-ulasan', [AdminController::class, 'dataUlasan'])->name('admin.data-ulasan');
Route::post('/admin/ulasan/balas', [AdminController::class, 'balasUlasan'])->name('admin.ulasan.balas');

// Pembayaran Pesanan
Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran');
Route::post('/pembayaran/{id}/upload', [PembayaranController::class, 'store'])->name('pembayaran.upload');

// Ulasan (Review) Routes
Route::get('/ulasan/{formulirId}', [UlasanController::class, 'createOrEdit'])->name('user.ulasan.form');
Route::post('/ulasan/{formulirId}', [UlasanController::class, 'store'])->name('user.ulasan.store');
Route::put('/ulasan/{formulirId}', [UlasanController::class, 'update'])->name('user.ulasan.update');
Route::delete('/ulasan/{formulirId}/delete-image/{imageNumber}', [UlasanController::class, 'deleteImage'])->name('user.ulasan.delete-image');

// Public: Lihat ulasan per kostum
Route::get('/lihat-ulasan/{id_kostum}', [UlasanController::class, 'lihatUlasanKostum'])->name('lihat-ulasan');


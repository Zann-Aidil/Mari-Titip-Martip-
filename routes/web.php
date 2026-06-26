<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OnopayController;


// OnoPay Payment Routes
Route::prefix('onopay')->group(function () {
    Route::get('check-user', [OnopayController::class, 'checkUser']);
    Route::get('check-balance', [OnopayController::class, 'checkBalance']);
    Route::post('topup', [OnopayController::class, 'topup']);
    Route::post('generate-qr', [OnopayController::class, 'generateQR']);
    Route::post('pay', [OnopayController::class, 'pay']);
    Route::get('transactions', [OnopayController::class, 'getTransactions']);
    Route::get('transaction/{id}', [OnopayController::class, 'getTransactionDetail']);
});

// Public Routes
Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/cari-lokasi', [PublicController::class, 'cariLokasi'])->name('cari.lokasi');
Route::get('/tentang-kami', [PublicController::class, 'tentangKami'])->name('tentang.kami');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');

// Public API Routes
Route::get('/api/search-lokasi', [PublicController::class, 'searchApi'])->name('api.search.lokasi');
Route::post('/api/mobile/pay', [PaymentController::class, 'mobilePay'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/onopay/webhook', [PaymentController::class, 'onopayWebhook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Area Routes (Requires Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/lokasi', [UserController::class, 'lokasi'])->name('user.lokasi');
    Route::get('/user/lokasi/{id}', [UserController::class, 'detailLokasi'])->name('user.lokasi.detail');
    Route::get('/user/titip/{id}', [UserController::class, 'formTitipan'])->name('user.titip.form');
    Route::post('/user/titip/{id}', [UserController::class, 'storeTitipan'])->name('user.titip.store');
    Route::get('/user/tracking/{id?}', [UserController::class, 'tracking'])->name('user.tracking');
    Route::get('/user/riwayat', [UserController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/user/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::put('/user/profil', [UserController::class, 'updateProfil'])->name('user.profil.update');

    // Payment & QR Routes
    Route::get('/user/pembayaran', [PaymentController::class, 'pembayaran'])->name('user.pembayaran');
    Route::post('/user/bayar', [PaymentController::class, 'bayar'])->name('user.bayar');
    Route::get('/user/qr-ambil', [PaymentController::class, 'qrAmbil'])->name('user.qr_ambil');
    Route::get('/api/payment/status/{trackId}', [PaymentController::class, 'checkStatus']);

    // Onopay — generate QR, cek saldo & proses pembayaran real dari saldo user
    Route::post('/api/payment/generate-qr', [PaymentController::class, 'generateQrApi']);
    Route::get('/api/user/onopay-balance', [PaymentController::class, 'checkUserBalance']);
    Route::post('/api/payment/process', [PaymentController::class, 'processPayment']);
});

// Admin Area Routes (Requires Authentication + Admin Role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    
    // Admin CRUD Routes untuk User
    Route::get('/users', [AdminController::class, 'dataUser'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Admin CRUD Routes untuk Location
    Route::get('/locations', [AdminController::class, 'dataLokasi'])->name('locations.index');
    Route::post('/locations', [AdminController::class, 'storeLokasi'])->name('locations.store');
    Route::put('/locations/{id}', [AdminController::class, 'updateLokasi'])->name('locations.update');
    Route::delete('/locations/{id}', [AdminController::class, 'destroyLokasi'])->name('locations.destroy');
    
    // Admin CRUD Routes untuk Deposit
    Route::get('/deposits', [AdminController::class, 'dataTitipan'])->name('deposits.index');
    Route::put('/deposits/{id}/status', [AdminController::class, 'updateDepositStatus'])->name('deposits.update_status');
    
    // Export Routes
    Route::get('/export/pdf', [AdminController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/csv', [AdminController::class, 'exportCsv'])->name('export.csv');
});

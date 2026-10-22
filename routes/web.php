<?php

use App\Http\Controllers\Auth\loginControllers;
use App\Http\Controllers\Auth\registerControllers;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Kategori\KategoriPemasukanController;
use App\Http\Controllers\Kategori\KategoriPengeluaranController;
use App\Http\Controllers\LaporanPemasukan\LaporanPemasukanController;
use App\Http\Controllers\LaporanPengeluaran\LaporanPengeluaranController;
use App\Http\Controllers\Pemasukan\PemasukanController;
use App\Http\Controllers\Pengeluaran\PengeluaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tabungan\ControllerTabungan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// 1. RUTE HALAMAN UTAMA (Di luar middleware guest)
Route::get('/', function () {
    // Jika sudah login, arahkan ke rute intended (atau ke dashboard jika tidak ada intended url)
    if (Auth::check()) {
        return redirect()->intended('/dashboard/index');
    }
    
    return redirect()->route('login');
});

Route::middleware('guest')->group(function(){
    //===========================================================================================
   
    Route::get('login',[loginControllers::class, 'index'])->name('login');
    Route::post('login',[loginControllers::class, 'auth'])->name('login.auth');
    
    Route::get('register',[registerControllers::class, 'index'])->name('register.index');
    Route::post('register/store',[registerControllers::class, 'store'])->name('register.store');
});

// ===========================================================================================

Route::middleware(['auth'])->group(function(){

Route::post('logout', [loginControllers::class, 'logout'])->name('logout');
// Route::get('profile',[ProfileController::class, 'index'])->name('profile.index');
Route::delete('logout/sistem',[loginControllers::class,'logout'])->name('logout_sistem');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

// ==========================================================================================
// ROUTE LIST DASHBOARD
// ==========================================================================================
Route::get('dashboard/index',[DashboardController::class, 'index'])->name('dashboard.home');
// =========================================================================================

// ==========================================================================================
// ROUTE LIST PEMASUKAN
// ==========================================================================================
Route::get('pemasukan/index',[PemasukanController::class, 'index'])->name('pemasukan.index');
Route::get('pemasukan/create',[PemasukanController::class, 'create'])->name("pemasukan.create");
Route::post('pemasukan/store',[PemasukanController::class,'store'])->name('pandapatan.store');
Route::get('pemasukan/edit/{id}',[PemasukanController::class,'edit'])->name('pemasukan.edit');
Route::put('pemasukan/update/{id}',[PemasukanController::class,'update'])->name('pemasukan.update');

// =========================================================================================
// ==========================================================================================
// ROUTE LIST PENGELUARAN
// ==========================================================================================
Route::get('pengeluaran/index',[PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::get('pengeluaran/create',[PengeluaranController::class, 'create'])->name("pengeluaran.create");
Route::post('pengeluaran/store',[PengeluaranController::class,'store'])->name('pengeluaran.store');
Route::get('pengeluaran/edit/{id}',[PengeluaranController::class,'edit'])->name('pengeluaran.edit');
Route::put('pengeluaran/update/{id}',[PengeluaranController::class,'update'])->name('pengeluaran.update');



// ROUTE KATEGORI PEMASUKAN
// ==========================================================================================
Route::get('kategoriPemasukan/index',[KategoriPemasukanController::class, 'index'])->name('kategoripemasukan.index');
Route::post('kategoriPemasukan/store',[KategoriPemasukanController::class, 'store'])->name('kategoripemasukan.store');
Route::delete('kategoriPemasukan/delete/{id}',[KategoriPemasukanController::class, 'delete'])->name('kategoripemasukan.delete');
Route::put('kategoriPemasukan/update/{id}',[KategoriPemasukanController::class, 'update'])->name('kategoripemasukan.update');

// =========================================================================================

// ROUTE KATEGORI PENGELUARAN
// ==========================================================================================
Route::get('kategoriPengeluaran/index',[KategoriPengeluaranController::class, 'index'])->name('kategoripengeluaran.index');
Route::post('kategoriPengeluaran/store',[KategoriPengeluaranController::class, 'store'])->name('kategoripengeluaran.store');
Route::delete('kategoriPengeluaran/delete/{id}',[KategoriPengeluaranController::class, 'delete'])->name('kategoripengeluaran.delete');
Route::put('kategoriPengeluaran/update/{id}',[KategoriPengeluaranController::class, 'update'])->name('kategoripengeluaran.update');


// =========================================================================================
// ROUTE LAPORAN PEMASUKAN
// =========================================================================================

// Menampilkan halaman laporan pemasukan.
Route::get(
    'laporanpemasukan/index',
    [LaporanPemasukanController::class, 'index']
)->name('laporanpemasukan.index');
// Mengunduh laporan pemasukan dalam format CSV yang kompatibel dengan Excel.
Route::get(
    'laporanpemasukan/export/excel',
    [LaporanPemasukanController::class, 'exportExcel']
)->name('laporanpemasukan.export.excel');

// Menampilkan laporan dalam format cetak untuk disimpan sebagai PDF.
Route::get(
    'laporanpemasukan/export/pdf',
    [LaporanPemasukanController::class, 'exportPdf']
)->name('laporanpemasukan.export.pdf');

// =========================================================================================
// =========================================================================================
// ROUTE LAPORAN PENGELUARAN
// ==========================================================================================
Route::get('laporanpengeluaran/index',[LaporanPengeluaranController::class, 'index'])->name('laporanpengeluaran.index');

// Mengunduh laporan sesuai filter aktif dalam format CSV yang kompatibel dengan Excel.
Route::get(
    'laporanpengeluaran/export/excel',
    [LaporanPengeluaranController::class, 'exportExcel']
)->name('laporanpengeluaran.export.excel');

// Membuka halaman cetak agar pengguna dapat memilih Save as PDF dari browser.
Route::get(
    'laporanpengeluaran/export/pdf',
    [LaporanPengeluaranController::class, 'exportPdf']
)->name('laporanpengeluaran.export.pdf');

// =========================================================================================
}
);
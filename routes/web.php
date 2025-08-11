<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PengumumanKelasController;
use App\Http\Controllers\SiswaJadwalController;
use App\Http\Controllers\SiswaNilaiController;
use App\Http\Controllers\SiswaAbsensiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('admin')->group(function () {
        Route::resource('siswa', SiswaController::class);
        Route::resource('guru', GuruController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('jadwal', JadwalController::class);
        Route::resource('pengumuman', PengumumanController::class);
    });
});

// Route untuk guru
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('guru')->group(function () {
        // Jadwal mengajar
        Route::get('jadwal-mengajar', [JadwalController::class, 'jadwalMengajar'])->name('guru.jadwal');
        
        // Absensi
        Route::resource('absensi', AbsensiController::class);
        Route::get('get-siswa-by-jadwal', [AbsensiController::class, 'getSiswaByJadwal'])->name('absensi.get-siswa');
        
        // Nilai
        Route::resource('nilai', NilaiController::class);
        Route::get('get-siswa-by-jadwal-nilai', [NilaiController::class, 'getSiswaByJadwal'])->name('nilai.get-siswa');
        
        // Materi
        Route::resource('materi', MateriController::class);
        
        // Pengumuman kelas
        Route::resource('pengumuman-kelas', PengumumanKelasController::class);
        

    });
});

// Route untuk siswa
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('siswa')->group(function () {
        // Jadwal pelajaran
        Route::get('jadwal-siswa', [SiswaJadwalController::class, 'index'])->name('siswa.jadwal');
        
        // Nilai
        Route::get('nilai-siswa', [SiswaNilaiController::class, 'index'])->name('siswa.nilai');
        
        // Absensi
        Route::get('absensi-siswa', [SiswaAbsensiController::class, 'index'])->name('siswa.absensi');
        
        // Materi
        Route::get('materi-siswa', [MateriController::class, 'materiSiswa'])->name('siswa.materi');
        
        // Pengumuman
        Route::get('pengumuman-siswa', [PengumumanKelasController::class, 'pengumumanSiswa'])->name('siswa.pengumuman');
    });
});

require __DIR__.'/auth.php';

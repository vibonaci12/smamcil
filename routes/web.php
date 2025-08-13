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
use App\Http\Controllers\JenisPenilaianController;

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
        Route::resource('jenis-penilaian', JenisPenilaianController::class);
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
        
        // Nilai - Batch Input System
        Route::get('nilai/select', [NilaiController::class, 'select'])->name('nilai.select');
        Route::get('nilai/create-batch', [NilaiController::class, 'createBatch'])->name('nilai.createBatch');
        Route::post('nilai/store-batch', [NilaiController::class, 'storeBatch'])->name('nilai.storeBatch');
        Route::get('nilai/edit-batch', [NilaiController::class, 'editBatch'])->name('nilai.editBatch');
        Route::put('nilai/update-batch', [NilaiController::class, 'updateBatch'])->name('nilai.updateBatch');
        Route::get('get-siswa-by-jadwal-nilai', [NilaiController::class, 'getSiswaByJadwal'])->name('nilai.get-siswa');
        Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
        
        // Nilai - New Features
        Route::get('nilai/transkrip/{siswaId}', [NilaiController::class, 'transkripSiswa'])->name('nilai.transkrip');
        Route::get('nilai/akhir-kelas/{kelasId}', [NilaiController::class, 'nilaiAkhirKelas'])->name('nilai.akhir-kelas');
        
        // API for dropdown data
        Route::get('get-kelas-guru', [NilaiController::class, 'getKelasGuru'])->name('nilai.get-kelas');
        Route::get('get-siswa-guru', [NilaiController::class, 'getSiswaGuru'])->name('nilai.get-siswa');
        
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
        Route::get('nilai-siswa/transkrip', [SiswaNilaiController::class, 'transkrip'])->name('siswa.nilai.transkrip');
        Route::get('nilai-siswa/akhir', [SiswaNilaiController::class, 'nilaiAkhir'])->name('siswa.nilai.akhir');
        
        // Absensi
        Route::get('absensi-siswa', [SiswaAbsensiController::class, 'index'])->name('siswa.absensi');
        
        // Materi
        Route::get('materi-siswa', [MateriController::class, 'materiSiswa'])->name('siswa.materi');
        
        // Pengumuman
        Route::get('pengumuman-siswa', [PengumumanKelasController::class, 'pengumumanSiswa'])->name('siswa.pengumuman');
    });
});

require __DIR__.'/auth.php';

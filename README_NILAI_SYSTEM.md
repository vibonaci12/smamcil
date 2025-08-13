# Sistem Penilaian yang Ditingkatkan

## Fitur Baru yang Ditambahkan

### 1. Service Layer
- **GuruService**: Menangani logika autentikasi dan verifikasi guru
- **NilaiService**: Menangani perhitungan nilai akhir dan transkrip

### 2. Form Request Validation
- **StoreBatchRequest**: Validasi untuk input nilai batch
- **UpdateBatchRequest**: Validasi untuk update nilai batch

### 3. Perhitungan Nilai Akhir Otomatis
- Sistem menghitung nilai akhir berdasarkan bobot dari database
- Bobot dapat diatur per jenis penilaian (Tugas, UTS, UAS)
- Perhitungan menggunakan rumus: `(nilai1 * bobot1 + nilai2 * bobot2 + ...) / total_bobot * 100`

### 4. Fitur Transkrip Siswa
- Menampilkan semua mata pelajaran beserta nilai akhir
- Menghitung rata-rata nilai per siswa
- Menampilkan status nilai (Sangat Baik, Baik, Cukup, Kurang)
- Fitur cetak transkrip

### 5. Nilai Akhir Kelas
- Menampilkan nilai akhir semua siswa dalam satu kelas
- Statistik kelas (rata-rata, persentase kelengkapan)
- Fitur cetak nilai akhir kelas

### 6. Konfigurasi Bobot Nilai
- File konfigurasi `config/nilai.php`
- Kriteria nilai dapat diatur
- Bobot default dapat disesuaikan

## Cara Penggunaan

### 1. Input Nilai Batch
1. Guru login ke sistem
2. Klik "Input Nilai Batch" di halaman nilai
3. Pilih jadwal dan jenis penilaian
4. Input nilai untuk semua siswa sekaligus
5. Sistem akan menghitung nilai akhir otomatis

### 2. Lihat Transkrip Siswa
1. Klik dropdown "Laporan" di halaman nilai
2. Pilih "Transkrip Siswa"
3. Pilih siswa yang ingin dilihat transkripnya
4. Sistem akan menampilkan transkrip lengkap

### 3. Lihat Nilai Akhir Kelas
1. Klik dropdown "Laporan" di halaman nilai
2. Pilih "Nilai Akhir Kelas"
3. Pilih kelas yang ingin dilihat
4. Sistem akan menampilkan nilai akhir semua siswa

### 4. Edit Nilai Batch
1. Klik "Edit Batch" pada baris nilai yang ingin diedit
2. Ubah nilai yang diperlukan
3. Sistem akan memperbarui nilai akhir otomatis

## Struktur File

```
app/
├── Services/
│   ├── GuruService.php          # Service untuk logika guru
│   └── NilaiService.php         # Service untuk perhitungan nilai
├── Http/
│   ├── Controllers/
│   │   └── NilaiController.php  # Controller yang diperbarui
│   └── Requests/
│       └── Nilai/
│           ├── StoreBatchRequest.php
│           └── UpdateBatchRequest.php
└── Models/
    ├── Nilai.php                # Model nilai
    ├── JenisPenilaian.php       # Model jenis penilaian
    └── ...

resources/views/guru/nilai/
├── index.blade.php              # Halaman utama nilai
├── transkrip.blade.php          # Halaman transkrip
└── nilai-akhir-kelas.blade.php  # Halaman nilai akhir kelas

config/
└── nilai.php                    # Konfigurasi bobot dan kriteria
```

## Keamanan

1. **Autentikasi Guru**: Hanya guru yang dapat mengakses fitur nilai
2. **Verifikasi Kepemilikan**: Guru hanya dapat menginput/edit nilai untuk jadwal yang dia ajar
3. **Validasi Input**: Semua input divalidasi menggunakan Form Request
4. **Eager Loading**: Query dioptimasi untuk menghindari N+1 problem

## Konfigurasi

### Mengubah Bobot Nilai
Edit file `config/nilai.php`:

```php
'default_bobot' => [
    'tugas' => 25,  // Ubah dari 30 ke 25
    'uts' => 35,    // Ubah dari 30 ke 35
    'uas' => 40,    // Tetap 40
],
```

### Mengubah Kriteria Nilai
Edit file `config/nilai.php`:

```php
'kriteria' => [
    'sangat_baik' => [
        'min' => 90,  // Ubah dari 85 ke 90
        'label' => 'Sangat Baik (A)',
        'color' => 'green'
    ],
    // ... kriteria lainnya
],
```

## API Endpoints

### Untuk Guru
- `GET /nilai` - Daftar nilai
- `GET /nilai/select` - Pilih jadwal untuk input nilai
- `GET /nilai/create-batch` - Form input nilai batch
- `POST /nilai/store-batch` - Simpan nilai batch
- `GET /nilai/edit-batch` - Form edit nilai batch
- `PUT /nilai/update-batch` - Update nilai batch
- `GET /nilai/transkrip/{siswaId}` - Transkrip siswa
- `GET /nilai/akhir-kelas/{kelasId}` - Nilai akhir kelas
- `GET /get-kelas-guru` - API data kelas guru
- `GET /get-siswa-guru` - API data siswa guru

## Database

### Tabel yang Digunakan
- `nilais` - Data nilai siswa
- `jenis_penilaians` - Jenis penilaian dan bobot
- `jadwals` - Jadwal mengajar
- `siswas` - Data siswa
- `kelas` - Data kelas
- `gurus` - Data guru

### Relasi
- Nilai → Siswa (belongsTo)
- Nilai → Jadwal (belongsTo)
- Nilai → JenisPenilaian (belongsTo)
- Jadwal → Guru (belongsTo)
- Jadwal → Kelas (belongsTo)
- Siswa → Kelas (belongsTo)

## Troubleshooting

### 1. Nilai Akhir Tidak Muncul
- Pastikan semua jenis penilaian sudah diinput
- Periksa bobot di tabel `jenis_penilaians`
- Pastikan total bobot tidak 0

### 2. Guru Tidak Bisa Akses Nilai
- Pastikan guru sudah login
- Periksa role user adalah 'guru'
- Pastikan data guru ada di tabel `gurus`

### 3. Error Validasi
- Periksa input nilai (0-100)
- Pastikan jadwal dan jenis penilaian valid
- Periksa apakah guru mengajar jadwal tersebut

## Pengembangan Selanjutnya

1. **Notifikasi**: Email/SMS ke orang tua saat nilai diinput
2. **Grafik**: Visualisasi statistik nilai
3. **Export**: Export nilai ke Excel/PDF
4. **Import**: Import nilai dari Excel
5. **Riwayat**: Riwayat perubahan nilai
6. **Backup**: Backup otomatis data nilai

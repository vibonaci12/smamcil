<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumumanKelas extends Model
{
    protected $fillable = [
        'kelas_id',
        'guru_id',
        'judul',
        'isi',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}

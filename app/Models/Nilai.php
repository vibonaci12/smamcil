<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'jenis_penilaian_id',
        'nilai'
    ];

    protected $casts = [
        'nilai' => 'decimal:2'
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    // Relasi ke JenisPenilaian
    public function jenisPenilaian()
    {
        return $this->belongsTo(JenisPenilaian::class);
    }

    // Accessor untuk nilai yang diformat
    public function getNilaiFormattedAttribute()
    {
        return number_format($this->nilai, 1);
    }

    // Accessor untuk status nilai
    public function getStatusAttribute()
    {
        if ($this->nilai >= 75) {
            return 'success';
        } elseif ($this->nilai >= 60) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}

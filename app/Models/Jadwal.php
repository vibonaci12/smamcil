<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mapel',
        'hari',
        'jam_mulai',
        'jam_selesai'
    ];
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
    public function materis()
    {
        return $this->hasMany(Materi::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    
    protected $fillable = [
        'nama',
        'wali_kelas_id'
    ];

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
    public function materis()
    {
        return $this->hasMany(Materi::class);
    }
    public function pengumumanKelas()
    {
        return $this->hasMany(PengumumanKelas::class);
    }
}

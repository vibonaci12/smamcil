<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nip',
        'nama',
        'mapel',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
        'user_id',
    ];
    
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }
    public function materis()
    {
        return $this->hasMany(Materi::class);
    }
    public function pengumumanKelas()
    {
        return $this->hasMany(PengumumanKelas::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

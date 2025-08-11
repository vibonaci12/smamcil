<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nis',
        'nama',
        'kelas_id',
        'jurusan',
        'alamat',
        'no_hp',
        'jenis_kelamin',
        'tanggal_lahir',
        'foto',
        'user_id',
    ];
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

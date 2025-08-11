<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'bobot'
    ];

    protected $casts = [
        'bobot' => 'decimal:2'
    ];

    // Relasi ke Nilai
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    // Accessor untuk nama yang diformat
    public function getNamaFormattedAttribute()
    {
        return ucfirst($this->nama);
    }

    // Accessor untuk bobot yang diformat
    public function getBobotFormattedAttribute()
    {
        return $this->bobot . '%';
    }
}

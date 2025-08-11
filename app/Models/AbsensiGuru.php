<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $table = 'absensi_gurus';
    
    protected $fillable = [
        'guru_id',
        'jadwal_id',
        'tanggal',
        'status',
        'keterangan',
        'materi_yang_diajarkan',
        'catatan_kbm'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}

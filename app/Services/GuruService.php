<?php

namespace App\Services;

use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

class GuruService
{
    /**
     * Get the authenticated guru or throw exception
     *
     * @return \App\Models\Guru
     * @throws \Exception
     */
    public static function getAuthenticatedGuru()
    {
        $user = Auth::user();
        
        if ($user->role !== 'guru') {
            throw new \Exception('Akses ditolak. Hanya guru yang dapat mengakses halaman ini.');
        }

        $guru = Guru::where('user_id', $user->id)->first();
        
        if (!$guru) {
            throw new \Exception('Data guru tidak ditemukan.');
        }

        return $guru;
    }

    /**
     * Check if the authenticated user is a guru
     *
     * @return bool
     */
    public static function isGuru()
    {
        $user = Auth::user();
        return $user && $user->role === 'guru';
    }

    /**
     * Get all schedule IDs taught by the authenticated guru
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getJadwalIds()
    {
        $guru = self::getAuthenticatedGuru();
        return $guru->jadwals()->pluck('id');
    }

    /**
     * Verify if a schedule belongs to the authenticated guru
     *
     * @param int $jadwalId
     * @return \App\Models\Jadwal|null
     */
    public static function verifyJadwalOwnership($jadwalId)
    {
        $guru = self::getAuthenticatedGuru();
        return $guru->jadwals()->where('id', $jadwalId)->first();
    }
}

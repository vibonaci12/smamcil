<?php

namespace App\Helpers;

use App\Services\NilaiService;
use App\Services\GuruService;

class NilaiHelper
{
    /**
     * Get formatted grade status with color
     *
     * @param float|null $nilai
     * @return array
     */
    public static function getStatusWithColor($nilai)
    {
        if ($nilai === null) {
            return [
                'label' => 'Belum ada nilai',
                'color' => 'gray',
                'bg_color' => 'bg-gray-100',
                'text_color' => 'text-gray-800'
            ];
        }

        if ($nilai >= 85) {
            return [
                'label' => 'Sangat Baik (A)',
                'color' => 'green',
                'bg_color' => 'bg-green-100',
                'text_color' => 'text-green-800'
            ];
        } elseif ($nilai >= 75) {
            return [
                'label' => 'Baik (B)',
                'color' => 'blue',
                'bg_color' => 'bg-blue-100',
                'text_color' => 'text-blue-800'
            ];
        } elseif ($nilai >= 60) {
            return [
                'label' => 'Cukup (C)',
                'color' => 'yellow',
                'bg_color' => 'bg-yellow-100',
                'text_color' => 'text-yellow-800'
            ];
        } else {
            return [
                'label' => 'Kurang (D)',
                'color' => 'red',
                'bg_color' => 'bg-red-100',
                'text_color' => 'text-red-800'
            ];
        }
    }

    /**
     * Format nilai dengan 2 desimal
     *
     * @param float|null $nilai
     * @return string
     */
    public static function formatNilai($nilai)
    {
        if ($nilai === null) {
            return '-';
        }
        return number_format($nilai, 2);
    }

    /**
     * Get grade letter from numeric value
     *
     * @param float|null $nilai
     * @return string
     */
    public static function getGradeLetter($nilai)
    {
        if ($nilai === null) {
            return '-';
        }

        if ($nilai >= 85) return 'A';
        if ($nilai >= 75) return 'B';
        if ($nilai >= 60) return 'C';
        return 'D';
    }

    /**
     * Check if user can access nilai
     *
     * @return bool
     */
    public static function canAccessNilai()
    {
        try {
            GuruService::getAuthenticatedGuru();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get current teacher's schedules
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getCurrentTeacherSchedules()
    {
        try {
            $guru = GuruService::getAuthenticatedGuru();
            return $guru->jadwals()->with(['kelas'])->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Calculate grade percentage
     *
     * @param float $nilai
     * @param float $maxNilai
     * @return float
     */
    public static function calculatePercentage($nilai, $maxNilai = 100)
    {
        if ($maxNilai == 0) return 0;
        return round(($nilai / $maxNilai) * 100, 2);
    }

    /**
     * Get grade statistics for a collection of grades
     *
     * @param \Illuminate\Support\Collection $nilais
     * @return array
     */
    public static function getGradeStatistics($nilais)
    {
        $validNilai = $nilais->filter(function($nilai) {
            return $nilai !== null && $nilai >= 0;
        });

        if ($validNilai->isEmpty()) {
            return [
                'count' => 0,
                'average' => null,
                'highest' => null,
                'lowest' => null,
                'passing_rate' => 0
            ];
        }

        $passingCount = $validNilai->filter(function($nilai) {
            return $nilai >= 60;
        })->count();

        return [
            'count' => $validNilai->count(),
            'average' => round($validNilai->avg(), 2),
            'highest' => $validNilai->max(),
            'lowest' => $validNilai->min(),
            'passing_rate' => round(($passingCount / $validNilai->count()) * 100, 2)
        ];
    }
}

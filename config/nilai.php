<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Bobot Nilai
    |--------------------------------------------------------------------------
    |
    | Konfigurasi default untuk bobot nilai per jenis penilaian.
    | Bobot ini akan digunakan jika tidak ada data bobot di database.
    |
    */
    'default_bobot' => [
        'tugas' => 30,
        'uts' => 30,
        'uas' => 40,
    ],

    /*
    |--------------------------------------------------------------------------
    | Kriteria Nilai
    |--------------------------------------------------------------------------
    |
    | Kriteria untuk menentukan status nilai berdasarkan nilai akhir.
    |
    */
    'kriteria' => [
        'sangat_baik' => [
            'min' => 85,
            'label' => 'Sangat Baik (A)',
            'color' => 'green'
        ],
        'baik' => [
            'min' => 75,
            'label' => 'Baik (B)',
            'color' => 'blue'
        ],
        'cukup' => [
            'min' => 60,
            'label' => 'Cukup (C)',
            'color' => 'yellow'
        ],
        'kurang' => [
            'min' => 0,
            'label' => 'Kurang (D)',
            'color' => 'red'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Perhitungan
    |--------------------------------------------------------------------------
    |
    | Pengaturan untuk perhitungan nilai akhir.
    |
    */
    'perhitungan' => [
        'decimal_places' => 2,
        'min_nilai' => 0,
        'max_nilai' => 100,
    ],
];

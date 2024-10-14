<?php

namespace Database\Factories;

use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataKuliahFactory extends Factory
{
    protected $model = MataKuliah::class;

    public function definition()
    {
        $mataKuliahList = [
            'Algoritma dan Struktur Data',
            'Pemrograman Berbasis Objek',
            'Sistem Basis Data',
            'Kecerdasan Buatan',
            'Pemrograman Web',
            'Rekayasa Perangkat Lunak',
            'Jaringan Komputer',
            'Sistem Operasi',
            'Analisis dan Perancangan Sistem',
            'Pengolahan Citra Digital',
            'Kriptografi dan Keamanan Informasi',
            'Sistem Terdistribusi',
            'Pemrograman Mobile',
            'Big Data dan Data Mining',
            'Interaksi Manusia dan Komputer',
            'Pemrograman Game',
            'Cloud Computing',
            'Arsitektur Komputer',
            'Manajemen Proyek Teknologi Informasi',
            'Sistem Informasi Geografis',
        ];

        return [
            'kode_matkul' => strtoupper($this->faker->unique()->bothify('MAT###')),
            'nama_matkul' => $this->faker->randomElement($mataKuliahList),
            'sks' => $this->faker->numberBetween(2, 4),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            SimulasiPerkuliahanSeeder::class
            // DosenSeeder::class,
            // MahasiswaSeeder::class,
            // MataKuliahSeeder::class,
            // KelompokSeeder::class,
            // JadwalSeeder::class,
            // NilaiSeeder::class,
            // AbsensiSeeder::class,
            // PerwalianSeeder::class,
            // BimbinganMahasiswaSeeder::class,
            // LogBimbinganMahasiswaSeeder::class,
            // BimbinganKPSeeder::class,
            // LogBimbinganKPSeeder::class,
            // PengabdianSeeder::class,
            // PublikasiSeeder::class,
            // PenelitianSeeder::class,
            // BahanAjarSeeder::class,
            // PenugasanSeeder::class,
            // IsianPenugasanMahasiswaSeeder::class,
        ]);
    }

}

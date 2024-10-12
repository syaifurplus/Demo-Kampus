<?php

use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Factories\Factory;

class NilaiFactory extends Factory
{
    protected $model = Nilai::class;

    public function definition()
    {
        return [
            'id_mahasiswa' => Mahasiswa::factory(),
            'id_kelompok' => Kelompok::factory(),
            'nilai_uts' => $this->faker->randomFloat(2, 40, 100),
            'nilai_uas' => $this->faker->randomFloat(2, 40, 100),
            'nilai_tugas_akhir' => $this->faker->randomFloat(2, 40, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

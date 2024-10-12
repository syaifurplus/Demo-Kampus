<?php

namespace Database\Factories;
use App\Models\BimbinganMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class BimbinganMahasiswaFactory extends Factory
{
    protected $model = BimbinganMahasiswa::class;

    public function definition()
    {
        return [
            'id_mahasiswa' => Mahasiswa::factory(),
            'id_dosen' => Dosen::factory(),
            'topik' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

use App\Models\Perwalian;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerwalianFactory extends Factory
{
    protected $model = Perwalian::class;

    public function definition()
    {
        return [
            'id_dosen' => Dosen::factory(),
            'id_mahasiswa' => Mahasiswa::factory(),
            'status_validasi' => $this->faker->randomElement(['Validasi', 'Belum Validasi']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

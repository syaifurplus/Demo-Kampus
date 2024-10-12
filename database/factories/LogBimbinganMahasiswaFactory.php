<?php

namespace Database\Factories;
use App\Models\LogBimbinganMahasiswa;
use App\Models\BimbinganMahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogBimbinganMahasiswaFactory extends Factory
{
    protected $model = LogBimbinganMahasiswa::class;

    public function definition()
    {
        return [
            'id_bimbingan' => BimbinganMahasiswa::factory(),
            'tanggal' => $this->faker->date(),
            'catatan' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

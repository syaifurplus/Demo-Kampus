<?php

namespace Database\Factories;
use App\Models\IsianPenugasanMahasiswa;
use App\Models\Penugasan;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class IsianPenugasanMahasiswaFactory extends Factory
{
    protected $model = IsianPenugasanMahasiswa::class;

    public function definition()
    {
        return [
            'id_penugasan' => Penugasan::factory(),
            'id_mahasiswa' => Mahasiswa::factory(),
            'jawaban' => $this->faker->paragraph,
            'nilai' => $this->faker->randomFloat(2, 0, 100),
            'tanggal_pengumpulan' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

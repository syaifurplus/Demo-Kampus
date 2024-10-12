<?php

namespace Database\Factories;
use App\Models\Penugasan;
use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenugasanFactory extends Factory
{
    protected $model = Penugasan::class;

    public function definition()
    {
        return [
            'id_kelompok' => Kelompok::factory(),
            'nama_tugas' => $this->faker->sentence,
            'deskripsi' => $this->faker->paragraph,
            'tenggat' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

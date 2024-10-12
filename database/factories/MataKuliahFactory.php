<?php

namespace Database\Factories;
use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataKuliahFactory extends Factory
{
    protected $model = MataKuliah::class;

    public function definition()
    {
        return [
            'kode_matkul' => strtoupper($this->faker->unique()->bothify('MAT###')),
            'nama_matkul' => $this->faker->words(3, true),
            'sks' => $this->faker->numberBetween(1, 4),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

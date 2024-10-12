<?php

namespace Database\Factories;
use App\Models\Penelitian;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenelitianFactory extends Factory
{
    protected $model = Penelitian::class;

    public function definition()
    {
        return [
            'id_dosen' => Dosen::factory(),
            'judul' => $this->faker->sentence,
            'tahun' => $this->faker->year,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

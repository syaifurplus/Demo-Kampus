<?php

use App\Models\Pengabdian;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengabdianFactory extends Factory
{
    protected $model = Pengabdian::class;

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

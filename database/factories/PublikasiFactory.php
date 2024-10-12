<?php

use App\Models\Publikasi;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublikasiFactory extends Factory
{
    protected $model = Publikasi::class;

    public function definition()
    {
        return [
            'id_dosen' => Dosen::factory(),
            'judul' => $this->faker->sentence,
            'jurnal' => $this->faker->word,
            'tahun' => $this->faker->year,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

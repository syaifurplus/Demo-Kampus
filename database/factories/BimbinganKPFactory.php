<?php

use App\Models\BimbinganKP;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class BimbinganKPFactory extends Factory
{
    protected $model = BimbinganKP::class;

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

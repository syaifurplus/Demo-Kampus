<?php

namespace Database\Factories;
use App\Models\LogBimbinganKP;
use App\Models\BimbinganKP;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogBimbinganKPFactory extends Factory
{
    protected $model = LogBimbinganKP::class;

    public function definition()
    {
        return [
            'id_bimbingan' => BimbinganKP::factory(),
            'tanggal' => $this->faker->date(),
            'catatan' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

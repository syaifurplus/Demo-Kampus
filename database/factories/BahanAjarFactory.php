<?php

namespace Database\Factories;
use App\Models\BahanAjar;
use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Factories\Factory;

class BahanAjarFactory extends Factory
{
    protected $model = BahanAjar::class;

    public function definition()
    {
        return [
            'id_kelompok' => Kelompok::factory(),
            'nama_bahan' => $this->faker->sentence,
            'tipe_bahan' => $this->faker->randomElement(['Dokumen', 'Video', 'Lainnya']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

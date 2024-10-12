<?php

use App\Models\Kelompok;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelompokFactory extends Factory
{
    protected $model = Kelompok::class;

    public function definition()
    {
        return [
            'id_matkul' => MataKuliah::factory(),
            'id_dosen' => Dosen::factory(),
            'nama_kelompok' => 'Kelompok ' . $this->faker->numberBetween(1, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
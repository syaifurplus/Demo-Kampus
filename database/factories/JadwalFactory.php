<?php

namespace Database\Factories;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;

    public function definition()
    {
        return [
            'id_matkul' => MataKuliah::factory(),
            'id_dosen' => Dosen::factory(),
            'id_kelompok' => Kelompok::factory(),
            'hari' => $this->faker->dayOfWeek,
            'jam_mulai' => $this->faker->time(),
            'jam_selesai' => $this->faker->time(),
            'ruang' => 'Ruang ' . $this->faker->numberBetween(1, 20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

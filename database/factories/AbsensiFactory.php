<?php

namespace Database\Factories;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    protected $model = Absensi::class;

    public function definition()
    {
        return [
            'id_mahasiswa' => Mahasiswa::factory(),
            'id_jadwal' => Jadwal::factory(),
            'tanggal' => $this->faker->date(),
            'status' => $this->faker->randomElement(['Hadir', 'Tidak Hadir']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

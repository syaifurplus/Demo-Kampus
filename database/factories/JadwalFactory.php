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
        // Array berisi nama-nama hari kerja
        $weekdays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        return [
            'id_matkul' => MataKuliah::factory(),
            'id_dosen' => Dosen::factory(),
            'id_kelompok' => Kelompok::factory(),
            'hari' => $this->faker->randomElement($weekdays),  // Memilih hari kerja secara acak
            'jam_mulai' => $this->faker->time('H:i:s', '08:00:00'),
            'jam_selesai' => $this->faker->time('H:i:s', '21:00:00'),
            'ruang' => 'Ruang ' . $this->faker->randomNumber(3),
        ];
    }
}

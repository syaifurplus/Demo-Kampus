<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Jadwal;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Jadwal::factory(20)->create();  // 20 jadwal
    }
}

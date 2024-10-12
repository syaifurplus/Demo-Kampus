<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Mahasiswa::factory(50)->create();  // 50 mahasiswa
    }
}

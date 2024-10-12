<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\LogBimbinganMahasiswa;

class LogBimbinganMahasiswaSeeder extends Seeder
{
    public function run()
    {
        \App\Models\LogBimbinganMahasiswa::factory(30)->create();  // 3 log per bimbingan
    }
}

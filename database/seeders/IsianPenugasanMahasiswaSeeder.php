<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\IsianPenugasanMahasiswa;

class IsianPenugasanMahasiswaSeeder extends Seeder
{
    public function run()
    {
        \App\Models\IsianPenugasanMahasiswa::factory(50)->create();  // 50 isian penugasan
    }
}

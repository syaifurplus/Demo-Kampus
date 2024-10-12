<?php

use Illuminate\Database\Seeder;
use App\Models\BimbinganMahasiswa;

class BimbinganMahasiswaSeeder extends Seeder
{
    public function run()
    {
        \App\Models\BimbinganMahasiswa::factory(10)->create();
    }
}

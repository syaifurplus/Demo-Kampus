<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\BimbinganKP;

class BimbinganKPSeeder extends Seeder
{
    public function run()
    {
        \App\Models\BimbinganKP::factory(10)->create();
    }
}

<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Kelompok;

class KelompokSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Kelompok::factory(15)->create();  // 15 kelompok
    }
}

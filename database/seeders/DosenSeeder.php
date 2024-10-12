<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Dosen::factory(10)->create();  // 10 dosen
    }
}

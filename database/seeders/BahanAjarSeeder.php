<?php

use Illuminate\Database\Seeder;
use App\Models\BahanAjar;

class BahanAjarSeeder extends Seeder
{
    public function run()
    {
        \App\Models\BahanAjar::factory(20)->create();  // 20 bahan ajar
    }
}


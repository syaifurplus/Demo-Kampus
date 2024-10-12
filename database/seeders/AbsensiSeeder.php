<?php

use Illuminate\Database\Seeder;
use App\Models\Absensi;

class AbsensiSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Absensi::factory(100)->create();  // 100 data absensi
    }
}

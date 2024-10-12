<?php

use Illuminate\Database\Seeder;
use App\Models\Nilai;

class NilaiSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Nilai::factory(50)->create();  // 50 nilai mahasiswa
    }
}

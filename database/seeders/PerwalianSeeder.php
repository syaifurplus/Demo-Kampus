<?php

use Illuminate\Database\Seeder;
use App\Models\Perwalian;

class PerwalianSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Perwalian::factory(30)->create();  // 30 data perwalian
    }
}

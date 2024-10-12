<?php

use Illuminate\Database\Seeder;
use App\Models\Penugasan;

class PenugasanSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Penugasan::factory(15)->create();  // 15 penugasan
    }
}

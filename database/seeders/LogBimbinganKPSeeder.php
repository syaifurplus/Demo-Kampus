<?php

use Illuminate\Database\Seeder;
use App\Models\LogBimbinganKP;

class LogBimbinganKPSeeder extends Seeder
{
    public function run()
    {
        \App\Models\LogBimbinganKP::factory(30)->create();  // 3 log per bimbingan KP
    }
}

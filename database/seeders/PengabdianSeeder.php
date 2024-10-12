<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PengabdianSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Pengabdian::factory(10)->create();
    }
}

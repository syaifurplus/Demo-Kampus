<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PublikasiSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Publikasi::factory(10)->create();
    }
}

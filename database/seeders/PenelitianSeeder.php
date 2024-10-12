<?php

use Illuminate\Database\Seeder;

class PenelitianSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Penelitian::factory(10)->create();
    }
}

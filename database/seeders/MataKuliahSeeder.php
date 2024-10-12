<?php

use Illuminate\Database\Seeder;
use App\Models\MataKuliah;

class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        \App\Models\MataKuliah::factory(10)->create();  // 10 mata kuliah
    }
}

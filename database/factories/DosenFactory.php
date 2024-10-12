<?php

namespace Database\Factories;
use App\Models\Dosen;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class DosenFactory extends Factory
{
    protected $model = Dosen::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->name,
            'nip' => $this->faker->unique()->numerify('#########'),
            'email' => $this->faker->unique()->safeEmail,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

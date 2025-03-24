<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // Default password for testing
            'phone' => '01' . $this->faker->numberBetween(0, 2) . $this->faker->numberBetween(10000000, 99999999),
            'price' => $this->faker->numberBetween(100, 1000),
            'description' => $this->faker->text(150),
            'image' => null,
            'latitude' => $this->faker->latitude(30.0444, 30.0544),
            'longitude' => $this->faker->longitude(31.2357, 31.2457),
            'specialty_id' => Specialty::inRandomOrder()->first()->id,
        ];
    }
}

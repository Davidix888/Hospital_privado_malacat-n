<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'id_cargo' => Cargo::factory(),
            'nombres' => fake()->firstName(),
            'apellidos' => fake()->lastName(),
            'dpi' => fake()->unique()->numerify('#############'),
            'direccion' => fake()->address(),
            'estado' => true,
        ];
    }
}

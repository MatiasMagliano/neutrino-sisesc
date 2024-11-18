<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

use App\Models\AnioLectivo;
use App\Models\Curso;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activo' => fake()->boolean(98),
            'dni' => fake()->unique()->numberBetween(10000000, 99999999),
            'nombre' => fake('es_ES')->firstName(),
            'apellido' => fake('es_ES')->lastName(),
            'email' => fake('es_ES')->unique()->safeEmail(),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake('es_ES')->address(),
            'egresado' => false, // Por defecto no egresado
            'observaciones' => fake('es_ES')->optional()->text(200),
        ];
    }
}

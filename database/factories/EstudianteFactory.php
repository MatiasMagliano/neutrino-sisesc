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
        // Obtener el curso desde los atributos del factory
        $curso = $this->faker->optional()->randomElement(Curso::all());
        $anioActual = now()->year;

        if ($curso) {
            // Determinar el rango de edad según el curso
            $rangoEdades = match ($curso->nombre) {
                '1º Año' => [11, 13],
                '2º Año' => [12, 14],
                '3º Año' => [13, 15],
                '4º Año' => [15, 17],
                '5º Año' => [16, 18],
                '6º Año' => [17, 19],
                default => [11, 19], // Rango general por seguridad
            };

            $edadMinima = $rangoEdades[0];
            $edadMaxima = $rangoEdades[1];

            // Generar fecha de nacimiento dentro del rango
            $fechaNacimiento = fake('es_ES')->dateTimeBetween(
                '-' . $edadMaxima . ' years',
                '-' . $edadMinima . ' years'
            )->format('Y-m-d');
        } else {
            // En caso de que no haya un curso (backup por consistencia)
            $fechaNacimiento = fake('es_ES')->date('Y-m-d', '-12 years');
        }

        return [
            'activo' => fake('es_ES')->boolean(95),
            'anio_lectivo_id' => null, // Será asignado en el seeder
            'curso_id' => $curso?->id, // Será asignado en el seeder
            'dni' => fake('es_ES')->unique()->numberBetween(10000000, 99999999),
            'nombre' => fake('es_ES')->firstName(),
            'apellido' => fake('es_ES')->lastName(),
            'f_nacimiento' => $fechaNacimiento,
            'email' => fake('es_ES')->unique()->safeEmail(),
            'telefono' => fake('es_ES')->phoneNumber(),
            'direccion' => fake('es_ES')->address(),
            'egresado' => false, // Por defecto no egresado
            'observaciones' => fake('es_ES')->optional()->text(200),
        ];
    }

    public function withCursoYAnio(Curso $curso, int $anioLectivoId): self
    {
        return $this->state(fn () => [
            'curso_id' => $curso->id,
            'anio_lectivo_id' => $anioLectivoId,
        ]);
    }
}

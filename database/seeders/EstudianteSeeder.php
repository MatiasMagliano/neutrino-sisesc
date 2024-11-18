<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\AnioLectivo;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = Curso::all();

        foreach($cursos as $curso)
        {
            $rangoEdades = match ($curso->nombre) {
                '1º Año' => [11, 12],
                '2º Año' => [12, 13],
                '3º Año' => [13, 14],
                '4º Año' => [15, 15],
                '5º Año' => [16, 16],
                '6º Año' => [17, 17],
                default => [11, 18], // Rango general por seguridad
            };

            $edadMinima = (int)(Carbon::now()->year - ($curso->anioLectivo->anio - $rangoEdades[0]));
            $edadMaxima = (int)(Carbon::now()->year - ($curso->anioLectivo->anio - $rangoEdades[1]));

            for($i = 0; $i < rand(23, 30); $i++)
            {
                Estudiante::factory()
                ->create([ // se agrega manualmente dada la correlación entre cursos y edades
                    'curso_id' => $curso->id,
                    'anio_lectivo_id' => $curso->anioLectivo->id,
                    'f_nacimiento' => fake()->dateTimeBetween(
                        '-' . $edadMaxima . ' years',
                        '-' . $edadMinima . ' years'
                    )->format('Y-m-d'),
                ]);
            }
        }
    }
}

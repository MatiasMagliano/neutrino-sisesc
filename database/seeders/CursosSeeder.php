<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursosSeeder extends Seeder
{
    public function run(): void
    {
        $aniosLectivos = \App\Models\AnioLectivo::all();

        foreach ($aniosLectivos as $anioLectivo) {
            // Ciclo Básico (CB) - 1º, 2º y 3º año
            $this->crearCursosCicloBasico($anioLectivo->id);

            // Ciclo de Especialización (CE) - 4º, 5º y 6º año
            $this->crearCursosCicloEspecializacion($anioLectivo->id);
        }
    }

    private function crearCursosCicloBasico($anioLectivoId)
    {
        // 1º Año - 4 divisiones
        for ($i = 1; $i <= 4; $i++) {
            Curso::create([
                'nombre' => '1º Año',
                'ciclo' => 'CB',
                'division' => chr(64 + $i), // A, B, C, D
                'turno' =>  $i % 2 === 0 ? 'tarde' : 'mañana',
                'anio_lectivo_id' => $anioLectivoId,
                'descripcion' => '1º Año del Ciclo Básico, División ' . chr(64 + $i),
            ]);
        }

        // 2º Año - 4 divisiones
        for ($i = 1; $i <= 4; $i++) {
            Curso::create([
                'nombre' => '2º Año',
                'ciclo' => 'CB',
                'division' => chr(64 + $i), // A, B, C, D
                'turno' =>  $i % 2 === 0 ? 'tarde' : 'mañana',
                'anio_lectivo_id' => $anioLectivoId,
                'descripcion' => '2º Año del Ciclo Básico, División ' . chr(64 + $i),
            ]);
        }

        // 3º Año - 3 divisiones
        for ($i = 1; $i <= 3; $i++) {
            Curso::create([
                'nombre' => '3º Año',
                'ciclo' => 'CB',
                'division' => chr(64 + $i), // A, B, C
                'turno' =>  $i % 2 === 0 ? 'tarde' : 'mañana',
                'anio_lectivo_id' => $anioLectivoId,
                'descripcion' => '3º Año del Ciclo Básico, División ' . chr(64 + $i),
            ]);
        }
    }

    private function crearCursosCicloEspecializacion($anioLectivoId)
    {
        // 4º, 5º y 6º Año - 2 divisiones cada uno
        for ($anio = 4; $anio <= 6; $anio++) {
            for ($i = 1; $i <= 2; $i++) {
                Curso::create([
                    'nombre' => "{$anio}º Año",
                    'ciclo' => 'CE',
                    'division' => chr(64 + $i), // A, B
                    'turno' =>  $i % 2 === 0 ? 'tarde' : 'mañana',
                    'anio_lectivo_id' => $anioLectivoId,
                    'descripcion' => "{$anio}º Año del Ciclo de Especialización, División " . chr(64 + $i),
                ]);
            }
        }
    }
}

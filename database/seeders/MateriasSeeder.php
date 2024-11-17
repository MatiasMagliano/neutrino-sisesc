<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Materia;
use App\Models\Curso;

class MateriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Materias comunes para 1º, 2º y 3º año
         $materiasComunes = [
            'LENGUA Y LITERATURA',
            'MATEMÁTICA',
            'CIUDADANÍA Y PARTICIPACIÓN',
            'EDUCACIÓN FÍSICA',
            'EDUCACIÓN TECNOLÓGICA',
            'LENGUA EXTRANJERA - INGLÉS',
        ];

        // Asigna materias comunes a los cursos de 1º, 2º y 3º año
        $cursosCB = Curso::where('ciclo', 'CB')->get();
        foreach ($cursosCB as $curso) {
            foreach ($materiasComunes as $materia) {
                Materia::create([
                    'nombre' => $materia,
                    'descripcion' => "$materia - Común a todos los años del Ciclo Básico",
                    'curso_id' => $curso->id, // Relaciona la materia con el curso
                ]);
            }
        }

        // Materias específicas por año
        $materiasPorAnio = [
            '1º Año' => [
                'CIENCIAS NATURALES - BIOLOGÍA',
                'CIENCIAS NATURALES - FÍSICA',
                'CIENCIAS SOCIALES - GEOGRAFÍA',
                'EDUCACIÓN ARTÍSTICA - ARTES VISUALES'
            ],
            '2º Año' => [
                'CIENCIAS SOCIALES - HISTORIA',
                'EDUCACIÓN ARTÍSTICA - MÚSICA',
                'CIUDADANÍA Y PARTICIPACIÓN',
            ],
            '3º Año' => [
                'FÍSICA',
                'QUÍMICA',
                'GEOGRAFÍA',
                'HISTORIA',
                'EDUCACIÓN ARTÍSTICA - MÚSICA',
                'FORMACIÓN PARA LA VIDA Y EL TRABAJO',
            ],
        ];

        // Asigna materias específicas a los cursos de cada año
        foreach ($materiasPorAnio as $anio => $materias) {
            $cursos = Curso::where('nombre', $anio)->get();
            foreach ($cursos as $curso) {
                foreach ($materias as $materia) {
                    Materia::create([
                        'nombre' => $materia,
                        'descripcion' => "$materia - Específica de $anio",
                        'curso_id' => $curso->id, // Relaciona la materia con el curso
                    ]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
         // Obtener el año lectivo activo
         $anioLectivo = AnioLectivo::activo()->first();

         if (!$anioLectivo) {
             $this->command->error('No hay un año lectivo activo. Por favor, crea uno primero.');
             return;
         }

         // Obtener todos los cursos y asignar estudiantes
         Curso::all()->each(function ($curso) use ($anioLectivo) {
            Estudiante::factory()
                ->count(30) // Crear 30 estudiantes por curso
                ->withCursoYAnio($curso, $anioLectivo->id) // Usar el estado del factory
                ->create();
        });
    }
}

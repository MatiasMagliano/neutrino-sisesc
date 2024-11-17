<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnioLectivo;

class AniosLectivosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anios = [
            2024 => ['fecha_inicio' => '2024-03-01', 'fecha_fin' => '2024-12-20'],
            2023 => ['fecha_inicio' => '2023-03-01', 'fecha_fin' => '2023-12-20'],
            2022 => ['fecha_inicio' => '2022-03-01', 'fecha_fin' => '2022-12-20'],
            2021 => ['fecha_inicio' => '2021-03-01', 'fecha_fin' => '2021-12-20'],
            2020 => ['fecha_inicio' => '2020-03-01', 'fecha_fin' => '2020-12-20'],
            2019 => ['fecha_inicio' => '2019-03-01', 'fecha_fin' => '2019-12-20'],
        ];

        foreach ($anios as $anio => $fechas) {
            AnioLectivo::create([
                'anio' => $anio,
                'fecha_inicio' => $fechas['fecha_inicio'],
                'fecha_fin' => $fechas['fecha_fin'],
                'activo' => $anio === 2024 ?? true,
                'descripcion' => fake()->paragraph(3),
            ]);
        }
    }
}

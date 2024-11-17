<?php

namespace App\Filament\Resources\AnioLectivoResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Curso;
use App\Models\Materia;
use Carbon\Carbon;

class AniosLectivosOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cursos por año lectivo '.Carbon::now()->year, Curso::whereHas('anioLectivo', function ($query) {
                $query->where('anio', '>=', Carbon::now()->year);
            })->count())
                ->url(route('filament.neutrinoadmin.resources.cursos.index')),

            Stat::make('Cantidad de materias', Materia::count()),

            Stat::make('Average time on page', '3:12'),
        ];
    }
}

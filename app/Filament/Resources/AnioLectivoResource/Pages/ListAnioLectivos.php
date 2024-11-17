<?php

namespace App\Filament\Resources\AnioLectivoResource\Pages;

use App\Filament\Resources\AnioLectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnioLectivos extends ListRecords
{
    protected static string $resource = AnioLectivoResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            AnioLectivoResource\Widgets\AniosLectivosOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\AnioLectivoResource\Pages;

use App\Filament\Resources\AnioLectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnioLectivo extends EditRecord
{
    protected static string $resource = AnioLectivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

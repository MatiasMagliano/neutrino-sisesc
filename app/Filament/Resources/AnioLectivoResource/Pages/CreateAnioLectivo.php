<?php

namespace App\Filament\Resources\AnioLectivoResource\Pages;

use App\Filament\Resources\AnioLectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnioLectivo extends CreateRecord
{
    // trait que redirecciona al INDEX de cada panel
    use \App\RedirectTrait;

    protected static string $resource = AnioLectivoResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        // Redirige al índice del recurso después de crear
        $this->redirect($this->getResource()::getUrl('index'));
    }
}

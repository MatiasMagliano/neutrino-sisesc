<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum Status: string implements HasLabel, HasColor
{
    case INACTIVO = '0';
    case ACTIVO = '1';

    public function getLabel(): string
    {
        return match ($this) {
            self::INACTIVO => 'Inactivo',
            self::ACTIVO => 'Activo',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INACTIVO => 'danger',
            self::ACTIVO => 'success',
        };
    }
}

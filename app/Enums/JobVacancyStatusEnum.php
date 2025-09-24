<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum JobVacancyStatusEnum:string implements HasLabel
{
    case Draft = 'draft';
    case Active = 'active';
    case Paused = 'paused';
    case Closed = 'closed';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Rascunho',
            self::Active => 'Ativa',
            self::Paused => 'Pausada',
            self::Closed => 'Encerrada',
            self::Cancelled => 'Cancelada',
        };
    }
}

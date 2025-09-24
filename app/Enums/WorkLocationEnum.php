<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorkLocationEnum: string implements HasLabel
{
    case OnSite = 'on_site';
    case Remote = 'remote';
    case Hybrid = 'hybrid';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::OnSite => 'Presencial',
            self::Remote => 'Remoto',
            self::Hybrid => 'Híbrido',
        };
    }
}

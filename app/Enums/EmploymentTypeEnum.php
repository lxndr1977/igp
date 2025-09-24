<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EmploymentTypeEnum: string implements HasLabel
{
    case CLT = 'clt';
    case ServiceProvider = 'service_provider';
    case Temporary = 'temporary';
    case SelfEmployed = 'self_employed';
    case Freelancer = 'freelancer';
    case Trainee = 'trainee';
    case Cooperated = 'cooperated';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CLT => 'CLT',
            self::ServiceProvider => 'Prestador de Serviços',
            self::Temporary => 'Temporário',
            self::SelfEmployed => 'Autônomo',
            self::Freelancer => 'Freelancer',
            self::Trainee => 'Trainee',
            self::Cooperated => 'Cooperado',
        };
    }
}



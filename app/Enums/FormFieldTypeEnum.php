<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum FormFieldTypeEnum: string implements HasLabel
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Email = 'email';
    case Number = 'number';
    case Tel = 'tel';
    case SelectSingle = 'select_single';
    case SelectMultiple = 'select_multiple';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case Date = 'date';
    case Rating = 'rating';
    case Scale = 'scale';

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => 'Texto',
            self::Textarea => 'Área de Texto',
            self::Email => 'E-mail',
            self::Number => 'Número',
            self::Tel => 'Telefone',
            self::SelectSingle => 'Seleção Única',
            self::SelectMultiple => 'Seleção Múltipla',
            self::Radio => 'Opção Única',
            self::Checkbox => 'Caixa de Seleção',
            self::Date => 'Data',
            self::Rating => 'Avaliação (Estrelas)',
            self::Scale => 'Escala (1-10)',
        };
    }
}

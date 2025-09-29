<?php

namespace App\Filament\Resources\FormResponses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FormResponseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subject_type')
                    ->required(),
                TextInput::make('subject_id')
                    ->required()
                    ->numeric(),
                TextInput::make('respondent_email')
                    ->email()
                    ->default(null),
                TextInput::make('respondent_name')
                    ->default(null),
                TextInput::make('ip_address')
                    ->default(null),
                TextInput::make('user_agent')
                    ->default(null),
                DateTimePicker::make('submitted_at')
                    ->required(),
            ]);
    }
}

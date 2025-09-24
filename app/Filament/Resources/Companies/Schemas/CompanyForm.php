<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Models\Company;
use App\Rules\ValidCnpj;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Hidden; // Importe o Hidden

class CompanyForm
{
   public static function configure(Schema $schema): Schema
   {
      return $schema
         ->components([
            Section::make('Identificação da empresa')
               ->description('Informações de identificação da empresa')
               ->columnSpanFull()
               ->columns(2)
               ->schema([
                  TextInput::make('cnpj')
                     ->label('CNPJ da Empresa')
                     ->helperText('Informe o CNPJ da empresa. Apenas números.')
                     ->mask('999999999999999')
                     ->required()
                     ->unique()
                     ->minLength(14)
                     ->maxLength(14)
                     ->rules([
                        new ValidCnpj(),
                     ]),

                  TextInput::make('name')
                     ->label('Razão Social')
                     ->required()
                     ->maxLength(255),

                  Toggle::make('is_active')
                     ->label('Empresa Ativa')
                     ->default(true)
                     ->required()
                     ->reactive() 
                     ->helperText(
                        fn(Get $get) => $get('is_active')
                           ? 'A empresa está ativa e poderá ser usada nos formulários.'
                           : 'A empresa está inativa e não poderá ser associada a novos formulários.'
                     ),

               ])
         ]);
   }
}

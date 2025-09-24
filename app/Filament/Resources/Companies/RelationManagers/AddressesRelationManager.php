<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use BackedEnum;
use TextInput\Mask;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\IconSize;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Companies\Schemas\CompanyAdressesForm;
use App\Filament\Resources\Companies\Schemas\CompanyAddressesForm;
use App\Filament\Resources\Companies\Tables\CompanyAddressesTable;

class AddressesRelationManager extends RelationManager
{
   protected static string $relationship = 'addresses';

   protected static ?string $title = 'Endereços';

   protected static string | BackedEnum | null $icon = 'heroicon-o-map-pin';

   public function form(Schema $schema): Schema
   {
      return $schema
         ->components([
            TextInput::make('name')
               ->label('Nome')
               ->required()
               ->minLength(3)
               ->maxLength(255),

            TextInput::make('street')
               ->label('Rua')
               ->maxLength(255),

            TextInput::make('number')
               ->label('Número')
               ->maxLength(20),

            TextInput::make('complement')
               ->label('Complemento')
               ->maxLength(100),

            TextInput::make('district')
               ->label('Bairro')
               ->maxLength(100),

            TextInput::make('city')
               ->label('Cidade')
               ->maxLength(100),

            TextInput::make('state')
               ->label('Estado')
               ->minLength(2)
               ->maxLength(2),

            TextInput::make('zip_code')
               ->label('CEP')
               ->mask('99999-999')
               ->minLength(9)
               ->maxLength(9),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('name')
         ->modelLabel('Endereço')
         ->pluralModelLabel('Endereços')
         ->recordTitle('Endereço')
         ->heading('Endereços')
         ->description('Gerencie os endereços da empresa')
         ->emptyStateHeading('Nenhum endereço cadastrado')
         ->emptyStateDescription('Comece adicionando um endereço de uma empresa.')
         ->emptyStateIcon('heroicon-o-map-pin')
         ->columns([
            TextColumn::make('name')
               ->label('Nome')
               ->sortable(),
            TextColumn::make('street')
               ->label('Rua')
               ->sortable(),
            TextColumn::make('number')
               ->label('Número'),
            TextColumn::make('complement')
               ->label('Complemento'),
            TextColumn::make('district')
               ->label('Bairro'),
            TextColumn::make('city')
               ->label('Cidade')
               ->sortable(),
            TextColumn::make('state')
               ->label('Estado')
               ->sortable(),
            TextColumn::make('zip_code')
               ->label('CEP'),
         ])
         ->filters([
            //
         ])
         ->headerActions([
            CreateAction::make(),
         ])
         ->recordActions([
            ActionGroup::make([
               EditAction::make(),
               DeleteAction::make(),
            ])
               ->icon('heroicon-m-ellipsis-vertical')
               ->iconSize(IconSize::Small)
               ->size(Size::Small)
               ->tooltip('Mais ações'),
         ])
         ->toolbarActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ]);
   }
}

<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\IconSize;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Companies\Tables\ContactsTable;
use App\Filament\Resources\Companies\Schemas\CompanyContactForm;
use App\Filament\Resources\Companies\Tables\CompanyContactsTable;

class ContactsRelationManager extends RelationManager
{
   protected static string $relationship = 'contacts';

   protected static ?string $title = 'Contatos';

   protected static string | BackedEnum | null $icon = 'heroicon-o-identification';

   public function form(Schema $schema): Schema
   {
      return $schema
         ->components([
            TextInput::make('name')
               ->label('Nome')
               ->required(),

            TextInput::make('department')
               ->label('Departamento')
               ->required(),

            TextInput::make('email')
               ->label('Email')
               ->email()
               ->required(),

            TextInput::make('phone')
               ->label('Whatsapp')
               ->helperText('Número com DDD, apenas números')
               ->tel()
               ->maxLength(15),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('name')
         ->modelLabel('Contato')
         ->pluralModelLabel('Contatos')
         ->recordTitle('Contato')
         ->defaultSort('name')
         ->heading('Contatos')
         ->description('Gerencie os canais de contato com a empresa')
         ->emptyStateHeading('Nenhum contato cadastrado')
         ->emptyStateDescription('Comece adicionando um contato de uma empresa.')
         ->emptyStateIcon('heroicon-o-identification')
         ->columns([
            TextColumn::make('name')
               ->label('Nome')
               ->sortable()
               ->searchable(),

            TextColumn::make('department')
               ->label('Departamento')
               ->searchable(),

            TextColumn::make('email')
               ->label('Email')
               ->searchable(),

            TextColumn::make('phone')
               ->label('Telefone')
               ->searchable()
               ->url(fn($record) => $record->phone ? "https://wa.me/55" . preg_replace('/\D/', '', $record->phone) : null)
               ->openUrlInNewTab(),

            TextColumn::make('created_at')
               ->label('Criado em')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
               ->label('Atualizado em')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
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

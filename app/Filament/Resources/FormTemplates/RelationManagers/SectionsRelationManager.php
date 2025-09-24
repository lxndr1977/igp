<?php

namespace App\Filament\Resources\FormTemplates\RelationManagers;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\DissociateBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;

class SectionsRelationManager extends RelationManager
{
   protected static string $relationship = 'sections';

   protected static ?string $title = 'Seções';

   protected static string | BackedEnum | null $icon = 'heroicon-o-squares-2x2';

   public function form(Schema $schema): Schema
   {
      return $schema
         ->components([
            TextInput::make('title')
               ->label('Nome da seção')
               ->columnSpanFull()
               ->required()
               ->maxLength(255),

            Textarea::make('description')
               ->label('Descrição da seção')
               ->columnSpanFull()
               ->rows(3)
               ->maxLength(65535)
               ->nullable(),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('title')
         ->recordTitle('Seção')
         ->modelLabel('Seção')
         ->pluralModelLabel('Seções')
         ->heading('Seções do formulário')
         ->description('Gerencie as seções do formulário')
         ->reorderable('order')
         ->reorderRecordsTriggerAction(
            fn(Action $action, bool $isReordering) => $action
               ->button()
               ->label($isReordering ? 'Disable reordering' : 'Enable reordering'),
         )
         ->columns([
            TextColumn::make('title')
               ->label('Nome da seção')
               ->sortable(),
            TextColumn::make('created_at')
               ->label('Criada')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
               ->label('Atualizada')
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
            EditAction::make(),
            DeleteAction::make(),
         ])
         ->toolbarActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ]);
   }
}

<?php

namespace App\Filament\Resources\FormTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FormTemplatesTable
{
   public static function configure(Table $table): Table
   {
      return $table
         ->modelLabel('Formulário')
         ->pluralModelLabel('Formulários')
         ->defaultSort('created_at', 'desc')
         ->emptyStateHeading('Nenhum modelo de formulário cadastrado')
         ->emptyStateDescription('Comece adicionando um modelo de formulário.')
         ->emptyStateIcon('heroicon-o-document-plus')
         ->columns([
            TextColumn::make('name')
               ->label('Nome do Formulário')
               ->searchable()
               ->sortable(),

            TextColumn::make('is_active')
               ->label('Status')
               ->badge()
               ->formatStateUsing(fn(bool $state): string => $state ? 'Ativo' : 'Inativo')
               ->colors([
                  'success' => true,
                  'danger' => false,
               ])
               ->icons([
                  'heroicon-o-check-circle' => true,
                  'heroicon-o-x-circle' => false,
               ]),

            TextColumn::make('created_at')
               ->label('Criado')
               ->since()
               ->sortable(),

            TextColumn::make('updated_at')
               ->label('Atualizado')
               ->since()
               ->sortable(),
         ])
         ->filters([
            //
         ])
         ->recordActions([
            EditAction::make(),
         ])
         ->toolbarActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ]);
   }
}

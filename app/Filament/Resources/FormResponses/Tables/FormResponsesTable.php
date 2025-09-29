<?php

namespace App\Filament\Resources\FormResponses\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\IconSize;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class FormResponsesTable
{
   public static function configure(Table $table): Table
   {
      return $table
         ->heading('Currículo')
         ->description('Gerencie os currículos enviados para as vagas de emprego')
         ->columns([

            TextColumn::make('respondent_name')
               ->label('Candidato')
               ->searchable()
               ->sortable(),

            TextColumn::make('subject.title')
               ->label('Vaga')
               ->searchable()
               ->sortable(),

            TextColumn::make('company_name')
               ->label('Empresa')
               ->searchable()
               ->sortable(),

            TextColumn::make('submitted_at')
               ->label('Enviado')
               ->since()
               ->sortable(),
         ])
         ->filters([
            //
         ])
         ->recordActions([

            Action::make('viewResponse')
               ->label('Currículo')
               ->icon('heroicon-o-document-text')
               ->url(fn($record) => route('form-response-details', [
                  'templateId' => $record->subject_id,
                  'responseId' => $record->id,
               ])),

            ActionGroup::make([
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

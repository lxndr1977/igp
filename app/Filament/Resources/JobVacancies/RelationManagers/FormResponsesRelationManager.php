<?php

namespace App\Filament\Resources\JobVacancies\RelationManagers;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\FormResponse;
use Filament\Actions\Action;
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
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\JobVacancies\Pages\JobVacancyResponses;

class FormResponsesRelationManager extends RelationManager
{
   protected static string $relationship = 'formResponses';

   protected static ?string $title = 'Candidatos';

   protected static string | BackedEnum | null $icon = 'heroicon-o-users';

   public function form(Schema $schema): Schema
   {
      return $schema
         ->components([
            TextInput::make('respondent_email')
               ->email(),
            TextInput::make('respondent_name'),
            TextInput::make('ip_address'),
            TextInput::make('user_agent'),
            DateTimePicker::make('submitted_at')
               ->required(),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('respondent_name')
         ->defaultSort('created_at', 'desc')
         ->heading('Listagem de candidatos')
         ->description('Relação de profissionais que se candidataram à vaga')
         ->columns([
            TextColumn::make('respondent_name')
               ->label('Candidato')
               ->sortable()
               ->searchable(),
            TextColumn::make('respondent_email')
               ->label('Email')
               ->sortable()
               ->searchable(),
            TextColumn::make('submitted_at')
               ->label('Enviada')
               ->since()
               ->sortable(),
            TextColumn::make('created_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
         ])
         ->filters([
            //
         ])
         ->headerActions([
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

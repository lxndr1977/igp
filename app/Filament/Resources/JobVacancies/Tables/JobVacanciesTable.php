<?php

namespace App\Filament\Resources\JobVacancies\Tables;

use App\Models\JobVacancy;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use App\Enums\JobVacancyStatusEnum;
use Filament\Support\Enums\IconSize;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Enums\Alignment;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class JobVacanciesTable
{
   public static function configure(Table $table): Table
   {
      return $table
         ->defaultSort('created_at', 'desc')
         ->heading('Vagas de emprego')
         ->description('Gerencie as vagas de emprego')
         ->columns([
            TextColumn::make('company.name')
               ->label('Empresa')
               ->searchable(),

            TextColumn::make('title')
               ->label('Título da vaga')
               ->searchable(),

            TextColumn::make('status')
               ->label('Status')

               ->badge(),

            TextColumn::make('form_responses_count')
               ->label('Candidatos')
               ->counts('formResponses')
               ->alignment(Alignment::Center)
               ->badge(),

            TextColumn::make('created_at')
               ->label('Criada')
               ->since()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
               ->label('Atualizada')
               ->dateTime()
               ->since()
               ->toggleable(isToggledHiddenByDefault: true),
         ])
         ->filters([
            SelectFilter::make('company.id')
               ->label('Empresa')
               ->relationship('company', 'name')
               ->preload()
               ->searchable(),

            SelectFilter::make('status')
               ->label('Status')
               ->options(JobVacancyStatusEnum::class),
         ])
         ->recordActions([
            Action::make('viewResponses')
               ->label('Candidatos')
               ->icon('heroicon-o-users')
               ->url(
                  fn(JobVacancy $record) =>
                  route('filament.pages.all-job-responses', ['formId' => $record->id])
               ),

            ActionGroup::make([
               EditAction::make(),

               DeleteAction::make()
                  ->label('Excluir')
                  ->icon('heroicon-o-trash')
                  ->color('danger')
                  ->tooltip('Remover este vínculo permanentemente')
                  ->requiresConfirmation()
                  ->modalHeading('Excluir Empresa')
                  ->modalDescription('Tem certeza que deseja excluir esta vaga? Esta ação não pode ser desfeita.')
                  ->modalSubmitActionLabel('Sim, Excluir')
                  ->modalCancelActionLabel('Cancelar')
                  ->successNotificationTitle(null)
                  ->action(function (JobVacancy $record): void {
                     try {
                        $record->delete();

                        Notification::make()
                           ->title('Formulário excluído!')
                           ->success()
                           ->send();
                     } catch (\Exception $e) {
                        Notification::make()
                           ->title('Não foi possível excluir')
                           ->body($e->getMessage())
                           ->danger()
                           ->send();
                     }
                  })
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

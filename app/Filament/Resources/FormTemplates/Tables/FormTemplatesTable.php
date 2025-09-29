<?php

namespace App\Filament\Resources\FormTemplates\Tables;

use Filament\Tables\Table;
use App\Models\FormTemplate;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\IconSize;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

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
            ActionGroup::make([
               EditAction::make(),

               DeleteAction::make()
                  ->label('Excluir')
                  ->icon('heroicon-o-trash')
                  ->color('danger')
                  ->tooltip('Remover este vínculo permanentemente')
                  ->requiresConfirmation()
                  ->modalHeading('Excluir Empresa')
                  ->modalDescription('Tem certeza que deseja excluir este formulário? Esta ação não pode ser desfeita.')
                  ->modalSubmitActionLabel('Sim, Excluir')
                  ->modalCancelActionLabel('Cancelar')
                  ->successNotificationTitle(null)
                  ->action(function (FormTemplate $record): void {
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

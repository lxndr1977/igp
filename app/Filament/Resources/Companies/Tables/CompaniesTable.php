<?php

namespace App\Filament\Resources\Companies\Tables;

use App\Models\Company;
use Filament\Tables\Table;
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
use Filament\Tables\Filters\SelectFilter;

class CompaniesTable
{
   public static function configure(Table $table): Table
   {
      return $table
         ->defaultSort('name')
         ->searchOnBlur()
         ->columns([
            TextColumn::make('cnpj')
               ->label('CNPJ')
               ->sortable()
               ->searchable(),

            TextColumn::make('name')
               ->label('Nome da empresa')
               ->sortable()
               ->searchable(),

            TextColumn::make('status')
               ->label('Status')
               ->badge()
               ->getStateUsing(fn($record) => $record->is_active ? 'Ativo' : 'Inativo')
               ->colors([
                  'success' => fn($state) => $state === 'Ativa',
                  'danger' => fn($state) => $state === 'Inativa',
               ]),

            TextColumn::make('created_at')
               ->label('Criada em')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
               ->label('Atualizada em')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
         ])
         ->filters([
            SelectFilter::make('is_active')
               ->label('Status')
               ->options([
                  1 => 'Ativa',
                  0 => 'Inativa'
               ])->default(true)
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
                  ->modalDescription('Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita.')
                  ->modalSubmitActionLabel('Sim, Excluir')
                  ->modalCancelActionLabel('Cancelar')
                  ->successNotificationTitle(null)
                  ->action(function (Company $record): void {
                     try {
                        $record->delete();

                        Notification::make()
                           ->title('Empresa excluído!')
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
               ->tooltip('Ações'),
         ])
         ->toolbarActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ]);
   }
}

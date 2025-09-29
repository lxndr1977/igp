<?php

namespace App\Filament\Resources\JobVacancies\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\JobVacancies\JobVacancyResource;

class EditJobVacancy extends EditRecord
{
   protected static string $resource = JobVacancyResource::class;

   protected function getHeaderActions(): array
   {
      return [
         Action::make('Voltar')
            ->url($this->getResource()::getUrl('index'))
            ->icon('heroicon-o-arrow-left')
            ->size('sm')
            ->color('gray'),
      ];
   }

   public function hasCombinedRelationManagerTabsWithContent(): bool
   {
      return true;
   }

   public function getContentTabLabel(): ?string
   {
      return 'Vaga';
   }

   public function getContentTabIcon(): string|BackedEnum|null
   {
      return 'heroicon-o-document-text';
   }
}

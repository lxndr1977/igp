<?php

namespace App\Filament\Resources\JobVacancies\Pages;

use App\Filament\Resources\JobVacancies\JobVacancyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use BackedEnum;

class EditJobVacancy extends EditRecord
{
   protected static string $resource = JobVacancyResource::class;

   protected function getHeaderActions(): array
   {
      return [
         DeleteAction::make(),
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

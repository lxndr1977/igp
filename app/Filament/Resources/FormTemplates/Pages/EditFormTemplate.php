<?php

namespace App\Filament\Resources\FormTemplates\Pages;

use App\Filament\Resources\FormTemplates\FormTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use BackedEnum;

class EditFormTemplate extends EditRecord
{
    protected static string $resource = FormTemplateResource::class;


   public function hasCombinedRelationManagerTabsWithContent(): bool
   {
      return true;
   }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getContentTabLabel(): ?string
   {
      return 'Formulário';
   }

   public function getContentTabIcon(): string|BackedEnum|null
   {
      return 'heroicon-o-document-text';
   }
}

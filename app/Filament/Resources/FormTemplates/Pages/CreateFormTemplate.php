<?php

namespace App\Filament\Resources\FormTemplates\Pages;

use App\Filament\Resources\FormTemplates\FormTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormTemplate extends CreateRecord
{
   protected static string $resource = FormTemplateResource::class;

   public function hasCombinedRelationManagerTabsWithContent(): bool
   {
      return true;
   }
}

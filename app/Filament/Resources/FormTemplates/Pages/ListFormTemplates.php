<?php

namespace App\Filament\Resources\FormTemplates\Pages;

use App\Filament\Resources\FormTemplates\FormTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFormTemplates extends ListRecords
{
    protected static string $resource = FormTemplateResource::class;


   public function hasCombinedRelationManagerTabsWithContent(): bool
   {
      return true;
   }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make()->extraAttributes(['class' => 'bg-primary-600 hover:bg-primary-700 text-white']),
            CreateAction::make(),
        ];
    }
}

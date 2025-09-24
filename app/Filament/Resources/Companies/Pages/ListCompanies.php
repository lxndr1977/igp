<?php

namespace App\Filament\Resources\Companies\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\Companies\CompanyResource;

class ListCompanies extends ListRecords
{
   protected static string $resource = CompanyResource::class;

   protected function getHeaderActions(): array
   {
      return [
         CreateAction::make(),
      ];
   }
}

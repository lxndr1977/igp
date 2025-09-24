<?php

namespace App\Filament\Resources\Companies\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\Companies\CompanyResource;


class EditCompany extends EditRecord
{
   protected static string $resource = CompanyResource::class;

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
      return 'Empresa';
   }

   public function getContentTabIcon(): string|BackedEnum|null
   {
      return 'heroicon-o-building-storefront';
   }
}

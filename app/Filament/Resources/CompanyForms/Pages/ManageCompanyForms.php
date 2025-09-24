<?php

namespace App\Filament\Resources\CompanyForms\Pages;

use App\Filament\Resources\CompanyForms\CompanyFormResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyForms extends ManageRecords
{
    protected static string $resource = CompanyFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

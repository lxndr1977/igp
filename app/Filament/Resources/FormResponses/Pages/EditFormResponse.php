<?php

namespace App\Filament\Resources\FormResponses\Pages;

use App\Filament\Resources\FormResponses\FormResponseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFormResponse extends EditRecord
{
    protected static string $resource = FormResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

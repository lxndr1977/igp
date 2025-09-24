<?php

namespace App\Filament\Resources\JobVacancies\Pages;

use App\Filament\Resources\JobVacancies\JobVacancyResource;
use App\Models\FormResponse;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class JobVacancyResponses extends Page
{
    use InteractsWithRecord;

    protected static string $resource = JobVacancyResource::class;

    protected string $view = 'filament.resources.job-vacancies.pages.job-vacancy-responses';

    public FormResponse $response; 

    public function mount(int|string $record, int|string $response): void
    {
        // $this->record = vaga (já vem do trait)
        $this->record = $this->resolveRecord($record);

        // carregar resposta específica
        $this->response = FormResponse::with(['formFieldResponses.formTemplateField'])
            ->findOrFail($response);
    }
}

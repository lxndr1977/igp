<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\FormResponse;
use Filament\Actions\Action;
use App\Models\CompanyForm;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\View\View;

class AllFormResponses extends Page implements HasTable
{
   use InteractsWithTable;

   protected static bool $isDiscovered = false;
   protected static bool $shouldRegisterNavigation = true;


   protected string $view = 'filament.pages.all-form-responses';

   protected static ?string $title = 'Lista de Respostas';

   protected static ?string $navigationLabel = 'Visualizar Respostas';

   protected ?string $subheading = 'Custom Page Subheading';

   // Parâmetro opcional
   public ?int $formId = null;

   // Para que Filament aceite parâmetro na URL
   protected static function getRoute(): string
   {
      return '/all-form-responses/{formId?}';
   }

   public function getHeader(): ?View
   {
      $companyName = null;
      $formTemplateName = null;
      $hasGeneralResponses = false;
      $hasJobResponses = false;
      $createdAt = null;

      if ($this->formId) {
         $formTemplate = CompanyForm::with(['company', 'formTemplate'])
            ->find($this->formId);

         $companyName = $formTemplate?->company?->name;
         $formTemplateName = $formTemplate?->formTemplate?->name;
         $createdAt = $formTemplate?->created_at;

         // Verifica se tem respostas diretas do formulário (formulário geral)
         $hasGeneralResponses = FormResponse::where('subject_type', \App\Models\CompanyForm::class)
            ->where('subject_id', $this->formId)
            ->exists();

         // Verifica se tem respostas através de vagas (formulário de vaga)
         $hasJobResponses = FormResponse::whereHasMorph(
            'subject',
            [\App\Models\JobVacancy::class],
            fn($q) => $q->where('form_template_id', $this->formId)
         )->exists();
      }

      return view('filament.header-form-responses', [
         'companyName' => $companyName,
         'formTemplateName' => $formTemplateName,
         'createdAt' => $createdAt,
         'formId' => $this->formId,
         'hasGeneralResponses' => $hasGeneralResponses,
         'hasJobResponses' => $hasJobResponses,
      ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->query(function () {
            $query = FormResponse::query()->with('subject.company');

            if ($this->formId) {
               $query->where(function ($q) {
                  // Respostas de CompanyForm
                  $q->where(function ($q2) {
                     $q2->where('subject_type', \App\Models\CompanyForm::class)
                        ->where('subject_id', $this->formId);
                  })
                     // Respostas de JobVacancy relacionadas a esse form template
                     ->orWhereHasMorph(
                        'subject',
                        [\App\Models\JobVacancy::class],
                        fn($q2) => $q2->where('form_template_id', $this->formId)
                     );
               });
            }

            return $query;
         })

         ->columns([
            TextColumn::make('respondent_name')->label('Respondente')->searchable(),
            TextColumn::make('respondent_email')->label('Email')->searchable(),
            TextColumn::make('company_name')->label('Empresa')->searchable(),
            TextColumn::make('form_type')->label('Tipo de Formulário'),
            TextColumn::make('submitted_at')->label('Enviado em')->dateTime()->sortable(),
         ])
         ->recordActions(
            [
               Action::make('viewResponse')
                  ->label('Ver Resposta')
                  ->icon('heroicon-o-eye')
                  ->url(fn($record) => route('form-response-details', [
                     'templateId' => $record->subject_id,
                     'responseId' => $record->id,
                  ])),
            ]
         );
   }
}

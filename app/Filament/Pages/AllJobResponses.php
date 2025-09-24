<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\JobVacancy;
use Filament\Tables\Table;
use App\Models\CompanyForm;
use App\Models\FormResponse;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class AllJobResponses extends Page implements HasTable, HasActions, HasSchemas
{
   use InteractsWithTable;
   use InteractsWithActions;
   use InteractsWithSchemas;

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
         $formTemplate = \App\Models\JobVacancy::with(['company', 'formTemplate'])
            ->find($this->formId);

         $companyName = $formTemplate?->company?->name;
         $formTemplateName = $formTemplate?->formTemplate?->name;
         $createdAt = $formTemplate?->created_at;


         // Verifica se tem respostas através de vagas (formulário de vaga)
         $hasJobResponses = FormResponse::whereHasMorph(
            'subject',
            [\App\Models\JobVacancy::class],
            fn($q) => $q->where('subject_id', $this->formId)
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
                  // Pega respostas de CompanyForm
                  $q->where(function ($q2) {
                     $q2->where('subject_type', \App\Models\JobVacancy::class)
                        ->where('subject_id', $this->formId);
                  });
               });
            }

            $query->orderBy('submitted_at', 'desc');

            return $query;
         })
         ->columns([
            TextColumn::make('respondent_name')
               ->label('Candidato')
               ->searchable()
               ->sortable(),

            TextColumn::make('respondent_email')
               ->label('Email')
               ->searchable()
               ->sortable(),


            TextColumn::make('submitted_at')
               ->label('Enviado')
               ->dateTime()
               ->since()
               ->sortable(),
         ])
         ->recordActions(
            [
               Action::make('viewResponse')
                  ->label('Currículo')
                  ->icon('heroicon-o-document-text')
                  ->color(Color::Emerald)    
                  ->url(fn($record) => route('form-response-details', [
                     'templateId' => $record->subject_id,
                     'responseId' => $record->id,
                  ])),

               ActionGroup::make([
                  DeleteAction::make(),
               ])
                  ->icon('heroicon-m-ellipsis-vertical')
                  ->iconSize(IconSize::Small)
                  ->size(Size::Small)
                  ->color(Color::Emerald)    
                  ->tooltip('Mais ações'),
            ]
         );
   }
}


<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Models\CompanyForm;
use Filament\Pages\Page;
use App\Models\FormResponse;
use App\Models\FormTemplate;
use App\Models\FormFieldResponse;
use App\Models\FormTemplateField;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class FormStatisticsPage extends Page implements HasForms
{
   use InteractsWithSchemas;

   protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedChartBar;
   protected string $view = 'filament.pages.form-statistics';
   protected static ?string $title = 'Estatísticas de Formulários';
   protected static ?string $navigationLabel = 'Estatísticas';

   public ?int $selectedFormId = null;
   public ?CompanyForm $selectedForm = null;
   public Collection $formResponses;
   public Collection $fieldStatistics;

   public function mount(): void
   {
      $this->formResponses = collect();
      $this->fieldStatistics = collect();
   }

   public function form(Schema $form): Schema
   {
      return $form
         ->schema([
            Select::make('selectedFormId')
               ->label('Selecionar Formulário')
               ->options(CompanyForm::pluck('title', 'id')->toArray())
               ->searchable()
               ->live()
               ->afterStateUpdated(fn($state) => $this->loadFormStatistics($state))
         ]);
   }

   public function loadFormStatistics(?int $formId): void
   {
      if (!$formId) {
         $this->selectedForm = null;
         $this->formResponses = collect();
         $this->fieldStatistics = collect();
         return;
      }

      // CORREÇÃO 1: Carregar relacionamentos corretos
      $this->selectedForm = CompanyForm::withCompanyForm()->with([
         'formTemplate.sections.fields', // Para acessar os campos através das seções
         'formResponses.formFieldResponses.formTemplateField'
      ])->find($formId);

      if (!$this->selectedForm) {
         return;
      }

      // CORREÇÃO 2: Buscar respostas através do relacionamento morfológico correto
      $this->formResponses = FormResponse::where('subject_type', CompanyForm::class)
         ->where('subject_id', $formId) // Usar subject_id diretamente
         ->with(['formFieldResponses.formTemplateField'])
         ->get();

      $this->generateFieldStatistics();
   }

   protected function generateFieldStatistics(): void
   {
      $this->fieldStatistics = collect();

      if (!$this->selectedForm || $this->formResponses->isEmpty()) {
         return;
      }

      // CORREÇÃO 3: Buscar campos através das seções (como no CompanyForm)
      $allFields = collect();

      if ($this->selectedForm->formTemplate && $this->selectedForm->formTemplate->sections) {
         foreach ($this->selectedForm->formTemplate->sections as $section) {
            if ($section->fields) {
               $allFields = $allFields->merge($section->fields);
            }
         }
      }

      foreach ($allFields as $field) {
         $fieldResponses = $this->formResponses
            ->flatMap(fn($response) => $response->formFieldResponses)
            ->where('form_template_field_id', $field->id);

         $statistics = $this->calculateFieldStatistics($field, $fieldResponses);

         if ($statistics) {
            $this->fieldStatistics->push($statistics);
         }
      }
   }

   protected function calculateFieldStatistics(FormTemplateField $field, Collection $responses): ?array
   {
      if ($responses->isEmpty()) {
         return null;
      }

      $totalResponses = $responses->count();
      $fieldType = $field->field_type;

      $statistics = [
         'field' => $field,
         'total_responses' => $totalResponses,
         'type' => $fieldType,
         'can_chart' => $this->canGenerateChart($fieldType),
      ];

      if ($this->canGenerateChart($fieldType)) {
         $statistics['chart_data'] = $this->generateChartData($responses);
      } else {
         $statistics['text_responses'] = $this->generateTextResponses($responses);
      }

      return $statistics;
   }

   protected function canGenerateChart(string $fieldType): bool
   {
      return in_array($fieldType, [
         'select',
         'select_single',
         'radio',
         'checkbox',
         'boolean',
         'rating'
      ]);
   }

   protected function generateChartData(Collection $responses): array
   {
      $valueCounts = collect();

      foreach ($responses as $response) {
         $value = $response->value;

         // CORREÇÃO 4: Verificar se value é JSON e decodificar
         if (is_string($value) && $this->isJson($value)) {
            $value = json_decode($value, true);
         }

         if (is_array($value)) {
            // Para checkboxes que podem ter múltiplos valores
            foreach ($value as $item) {
               $item = $item ?: 'Sem resposta';
               $valueCounts[$item] = ($valueCounts[$item] ?? 0) + 1;
            }
         } else {
            $value = $value ?: 'Sem resposta';
            $valueCounts[$value] = ($valueCounts[$value] ?? 0) + 1;
         }
      }

      return $valueCounts->map(function ($count, $value) use ($responses) {
         return [
            'label' => $value,
            'value' => $count,
            'percentage' => round(($count / $responses->count()) * 100, 1)
         ];
      })->values()->toArray();
   }

   protected function generateTextResponses(Collection $responses): array
   {
      return $responses->map(function ($response) {
         $value = $response->value;

         // CORREÇÃO 5: Verificar se value é JSON e decodificar
         if (is_string($value) && $this->isJson($value)) {
            $value = json_decode($value, true);
         }

         return [
            'value' => is_array($value)
               ? implode(', ', $value)
               : ($value ?: 'Sem resposta'),
            'submitted_at' => $response->formResponse->submitted_at->format('d/m/Y H:i')
         ];
      })->toArray();
   }

   // CORREÇÃO 6: Método helper para verificar se string é JSON
   private function isJson($string): bool
   {
      if (!is_string($string)) {
         return false;
      }

      json_decode($string);
      return json_last_error() === JSON_ERROR_NONE;
   }

   public function getTotalResponsesProperty(): int
   {
      return $this->formResponses->count();
   }

   public function getResponsesThisMonthProperty(): int
   {
      return $this->formResponses
         ->where('submitted_at', '>=', now()->startOfMonth())
         ->count();
   }

   public function getResponsesThisWeekProperty(): int
   {
      return $this->formResponses
         ->where('submitted_at', '>=', now()->startOfWeek())
         ->count();
   }
}

<?php

namespace App\Filament\Resources\JobVacancies\Schemas;

use Closure;
use App\Models\JobVacancy;
use Illuminate\Support\Str;
use App\Models\FormTemplate;
use Filament\Schemas\Schema;
use App\Enums\WorkLocationEnum;
use App\Enums\EmploymentTypeEnum;
use App\Enums\JobVacancyStatusEnum;
use App\Models\Company;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class JobVacancyForm
{
   public static function configure(Schema $schema): Schema
   {
      return $schema
         ->components([
            Section::make('Informações Básicas')
               ->columns(2)
               ->columnSpanFull()
               ->schema([
                  Select::make('company_id')
                     ->label('Empresa')
                     ->options(Company::active()->orderBy('name')->pluck('name', 'id')->toArray())
                     ->required()
                     ->reactive(),

                  Select::make('form_template_id')
                     ->label('Modelo de Formulário')
                     ->options(FormTemplate::vacancyForm()->pluck('name', 'id'))
                     ->required(),

                  Toggle::make('show_company_name')
                     ->label('Exibir nome da empresa no site?')
                     ->required()
                     ->columnSpanFull(),

                  TextInput::make('slug')
                     ->required()
                     ->unique(ignoreRecord: true)
                     ->maxLength(255),
               ]),

            Section::make('Descrição da Vaga')
               ->columnSpanFull()
               ->schema([
                  TextInput::make('title')
                     ->label('Título da Vaga')
                     ->required()
                     ->maxLength(255)
                     ->live(onBlur: true) // Atualiza quando o usuário sai do campo
                     ->afterStateUpdated(function (string $operation, $state, Set $set, Get $get) {
                        if ($operation !== 'create') {
                           return;
                        }

                        if (!$state) {
                           return;
                        }

                        // Gera o slug baseado no título
                        $slug = Str::slug($state);
                        $companyId = $get('company_id');

                        if ($companyId) {
                           // Verifica se já existe um slug igual para a mesma empresa
                           $existingSlug = JobVacancy::where('company_id', $companyId)
                              ->where('slug', $slug)
                              ->exists();

                           if ($existingSlug) {
                              $slug = $slug . '-' . uniqid();
                           }
                        }

                        $set('slug', $slug);
                     }),

                  RichEditor::make('description')
                     ->label('Descrição')
                     ->required()
                     ->columnSpanFull()
                     ->extraInputAttributes(['style' => 'min-height: 18em;'])
                     ->extraAttributes(['class' => 'toolbar-sm'])
                     ->toolbarButtons(
                        ['bold', 'italic', 'underline', 'subscript', 'superscript', 'bulletList', 'orderedList'],
                     ),

                  RichEditor::make('requirements')
                     ->label('Requisitos')
                     ->required()
                     ->columnSpanFull()
                     ->extraInputAttributes(['style' => 'min-height: 18em;'])
                     ->extraAttributes(['class' => 'toolbar-sm'])
                     ->toolbarButtons(
                        ['bold', 'italic', 'underline', 'subscript', 'superscript', 'bulletList', 'orderedList'],
                     ),

                  RichEditor::make('benefits')
                     ->label('Benefícios')
                     ->required()
                     ->columnSpanFull()
                     ->extraInputAttributes(['style' => 'min-height: 18em;'])
                     ->extraAttributes(['class' => 'toolbar-sm'])
                     ->toolbarButtons(
                        ['bold', 'italic', 'underline', 'subscript', 'superscript', 'bulletList', 'orderedList'],
                     ),
               ]),
            Section::make('Detalhes da Posição')
               ->columns(3)
               ->columnSpanFull()
               ->schema([

                  Select::make('employment_type')
                     ->label('Tipo de Emprego')
                     ->options(EmploymentTypeEnum::class)
                     ->required()
                     ->default(EmploymentTypeEnum::CLT->value),

                  Select::make('work_location')
                     ->label('Local de Trabalho')
                     ->options(WorkLocationEnum::class)
                     ->required()
                     ->default(WorkLocationEnum::OnSite->value),

                  TextInput::make('department')
                     ->label('Departamento'),

                  TextInput::make('level')
                     ->label('Nível')
                     ->columnSpan(2),
               ]),

            Section::make('Localização')
               ->columns(3)
               ->columnSpanFull()
               ->schema([
                  TextInput::make('city')
                     ->label('Cidade'),

                  TextInput::make('state')
                     ->label('Estado'),

                  TextInput::make('country')
                     ->label('País')
                     ->required()
                     ->default('BR'),
               ]),
            Section::make('Remuneração')
               ->columns(2)
               ->columnSpanFull()
               ->schema([
                  TextInput::make('salary_min')
                     ->label('Salário Mínimo')
                     ->numeric(),

                  TextInput::make('salary_max')
                     ->label('Salário Máximo')
                     ->numeric(),

                  Toggle::make('show_salary')
                     ->label('Exibir salário no site?')
                     ->required(),
               ]),

            Section::make('Configurações da Vaga')
               ->columns(2)
               ->columnSpanFull()
               ->schema([
                  DatePicker::make('application_deadline')
                     ->label('Prazo de Candidatura')
                     ->columnSpanFull(),

                  Select::make('status')
                     ->label('Status')
                     ->options(JobVacancyStatusEnum::class)
                     ->required()
                     ->default(JobVacancyStatusEnum::Active->value),

                  Toggle::make('is_featured')
                     ->label('Destacar vaga')
                     ->required(),
               ]),
         ]);
   }
}

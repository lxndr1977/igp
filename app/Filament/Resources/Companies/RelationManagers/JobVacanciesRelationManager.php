<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use BackedEnum;
use App\Models\JobVacancy;
use Filament\Tables\Table;
use App\Models\CompanyForm;
use Illuminate\Support\Str;
use App\Models\FormTemplate;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Enums\WorkLocationEnum;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use App\Enums\EmploymentTypeEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use App\Enums\JobVacancyStatusEnum;
use Filament\Support\Enums\IconSize;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Pages\AllFormResponses;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Actions\DissociateBulkAction;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Filters\SelectFilter;

class JobVacanciesRelationManager extends RelationManager
{
   protected static string $relationship = 'jobVacancies';

   protected static ?string $title = 'Vagas';

   protected static string | BackedEnum | null $icon = 'heroicon-o-users';

   protected function getTableRecordUrlUsing(): ?\Closure
   {
      return fn($record) => $this->getResource()::getUrl('edit', ['record' => $record]);
   }

   public function form(Schema $schema): Schema
   {

      return $schema
         ->components([

            Section::make('Informações Básicas')
               ->columns(2)
               ->columnSpanFull()
               ->schema([
                  Select::make('form_template_id')
                     ->label('Modelo de Formulário de Vaga')
                     ->options(FormTemplate::vacancyForm()
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray())
                     ->searchable()
                     ->preload()
                     ->nullable(),

               ]),

            Section::make('Descrição da Vaga')
               ->columnSpanFull()
               ->schema([
                  TextInput::make('title')
                     ->label('Título da Vaga')
                     ->required()
                     ->maxLength(255)
                     ->live(onBlur: true) // Atualiza quando o usuário sai do campo
                     ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        if ($operation !== 'create') {
                           return;
                        }

                        $company = $this->getOwnerRecord();
                        $slug = Str::slug($state);

                        $existingSlug = JobVacancy::where('company_id', $company->id)
                           ->where('slug', $slug)
                           ->exists();

                        if ($existingSlug) {
                           $slug = $slug . '-' . uniqid();
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

                  Textarea::make('requirements')
                     ->label('Requisitos')
                     ->columnSpanFull(),

                  Textarea::make('benefits')
                     ->label('Benefícios')
                     ->columnSpanFull(),
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

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('title')
         ->defaultSort('created_at', 'desc')
         ->heading('Vagas de emprego')
         ->description('Gerencie as vagas de emprego da empresa')
         ->modelLabel('Vaga')
         ->pluralModelLabel('Vagas')
         ->emptyStateHeading('Nenhuma vaga cadastrada')
         ->emptyStateDescription('Comece criando uma vaga para uma empresa.')
         ->emptyStateIcon('heroicon-o-users')
         ->columns([
            TextColumn::make('title')
               ->label('Título da Vaga')
               ->searchable()
               ->sortable(),

            TextColumn::make('slug')
               ->label('URL')
               ->formatStateUsing(fn($record) => "/{$record->slug}")
               ->copyable()
               ->copyableState(fn($record) => url("vagas/{$record->company_id}/{$record->slug}"))
               ->copyMessage('Copiado')
               ->searchable(),

            TextColumn::make('status')
               ->label('Status')
               ->badge(),

            TextColumn::make('form_responses_count')
               ->label('Candidatos')
               ->counts('formResponses')
               ->alignment(Alignment::Center)
               ->badge(),
               
            TextColumn::make('created_at')
               ->label('Criada')
               ->since()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
               ->label('Atualizada')
               ->dateTime()
               ->since()
               ->toggleable(isToggledHiddenByDefault: true),
         ])
         ->filters([
      
         ])
         ->headerActions([
            CreateAction::make()
               ->slideOver(),
         ])
         ->recordActions([
            Action::make('viewResponses')
               ->label('Candidatos')
               ->icon('heroicon-o-users')
               ->url(
                  fn(JobVacancy $record) =>
                  "/painel-igp/all-job-responses/form/{$record->id}"
               ),

            ActionGroup::make([
               EditAction::make()
                  ->slideOver(),

               DeleteAction::make(),
            ])
               ->icon('heroicon-m-ellipsis-vertical')
               ->iconSize(IconSize::Small)
               ->size(Size::Small)
               ->tooltip('Mais ações'),
         ])
         ->toolbarActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ]);
   }
}

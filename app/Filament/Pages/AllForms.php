<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\CompanyForm;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Resources\CompanyForms\CompanyFormResource;

class AllForms extends Page implements HasTable
{
   use InteractsWithTable;

   protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;
   protected string $view = 'filament.pages.all-forms';
   protected static ?string $title = 'Formulários';
   protected static ?string $navigationLabel = 'Formulários';
    protected static bool $isDiscovered = false;

   protected ?string $heading = 'Gestão de Formulários';
   protected ?string $subheading = 'Gerencie formulários e visualize respostas';

   public function table(Table $table): Table
   {
      return $table
         ->query($this->getTableQuery())
         ->defaultSort('created_at', 'desc')
         ->heading('Formulários')
         ->description('Navegue pela lista, acesse as respostas ou adicione um novo formulário.')
         ->emptyStateHeading('Nenhum formulário por aqui')
         ->emptyStateDescription('Comece adicionando um modelo de formulário a uma empresa.')
         ->emptyStateIcon('heroicon-o-document-plus')
         ->emptyStateActions([
            $this->getLinkAction(),
         ])

         ->columns([
            TextColumn::make('company.name')
               ->label('Empresa')
               ->sortable()
               ->searchable(),

            TextColumn::make('title')
               ->label('Título do Formulário')
               ->wrap()
               ->sortable()
               ->searchable(),

            TextColumn::make('slug')
               ->label('URL')
               ->formatStateUsing(fn($record) => "/{$record->slug}")
               ->copyable()
               ->copyableState(fn($record) => url("formularios/{$record->company_id}/{$record->slug}"))
               ->copyMessage('Copiado')
               ->searchable(),

            TextColumn::make('created_at')
               ->label('Criado')
               ->since()
               ->sortable()
               ->tooltip(fn($record) => 'Criado em: ' . $record->created_at->format('d/m/Y às H:i')),

            TextColumn::make('form_responses_count')
               ->label('Respostas')
               ->counts('formResponses')
               ->alignment(Alignment::Center)
               ->badge(),
         ])
         ->filters([
            // Seus filtros aqui...
         ])
         ->recordUrl(
            fn(CompanyForm $record): string => CompanyFormResource::getUrl('edit', ['record' => $record]),
         )

         ->recordActions([
            Action::make('viewResponses')
               ->label('Respostas')
               ->icon('heroicon-o-eye')
               ->iconSize(IconSize::Small)
               ->size(Size::Small)
               ->tooltip('Visualizar todas as respostas deste formulário')
               ->url(fn(CompanyForm $record) => route('filament.pages.all-form-responses', ['formId' => $record->id])),

            ActionGroup::make([
               $this->getEditAction(),
               $this->getDeleteAction()
            ])->tooltip('Mais Ações')
               ->icon('heroicon-m-ellipsis-vertical')
               ->iconSize(IconSize::Small)
               ->size(Size::Small),
         ])
         ->headerActions([
            $this->getLinkAction(),
         ]);
   }

   protected function getLinkAction(): Action
   {
      return Action::make('linkForm')
         ->label('Vincular Formulário')
         ->icon('heroicon-o-plus')
         ->color('primary')
         ->tooltip('Criar um novo vínculo entre empresa e formulário')
         ->schema($this->getFormSchema())
         ->action(function (array $data): void {
            CompanyForm::create($data);
            Notification::make()
               ->title('Formulário vinculado com sucesso!')
               ->body('O formulário foi associado à empresa e está pronto para uso.')
               ->success()
               ->send();
         });
   }

   protected function getEditAction(): Action
   {
      return Action::make('edit')
         ->label('Editar')
         ->icon('heroicon-m-pencil-square')
         ->iconSize(IconSize::Small)
         ->tooltip('Editar informações deste vínculo')
         ->fillForm(function (CompanyForm $record): array {
            return [
               'company_id' => $record->company_id,
               'form_template_id' => $record->form_template_id,
               'title' => $record->title,
               'slug' => $record->slug,
               'description' => $record->description,
            ];
         })
         ->schema($this->getFormSchema(true))
         ->action(function (array $data, CompanyForm $record): void {
            $record->update($data);

            Notification::make()
               ->title('Formulário atualizado!')
               ->body('As alterações foram salvas com sucesso.')
               ->success()
               ->send();
         })
         ->modalHeading('Editar Formulário')
         ->modalDescription('Atualize as informações do vínculo entre empresa e formulário.')
         ->modalSubmitActionLabel('Salvar Alterações')
         ->modalCancelActionLabel('Cancelar');
   }

   protected function getDeleteAction(): Action
   {
      return Action::make('delete')
         ->label('Excluir')
         ->icon('heroicon-o-trash')
         ->iconSize(\Filament\Support\Enums\IconSize::Small)
         ->color('danger')
         ->tooltip('Remover este vínculo permanentemente')
         ->requiresConfirmation()
         ->modalHeading('Excluir Formulário')
         ->modalDescription('Tem certeza que deseja excluir este formulário? Esta ação não pode ser desfeita.')
         ->modalSubmitActionLabel('Sim, Excluir')
         ->modalCancelActionLabel('Cancelar')
         ->action(function (CompanyForm $record): void {
            $companyName = $record->company->name;
            $formTitle   = $record->title;

            try {
               $record->delete();

               Notification::make()
                  ->title('Formulário excluído!')
                  ->body("O formulário '{$formTitle}' da empresa '{$companyName}' foi removido com sucesso.")
                  ->success()
                  ->send();
            } catch (\Throwable $e) {
               Notification::make()
                  ->title('Não foi possível excluir')
                  ->body("O formulário '{$formTitle}' não pode ser removido porque já possui respostas vinculadas.")
                  ->danger()
                  ->send();
            }
         });
   }

   protected function getFormSchema(bool $isEdit = false): array
   {
      $generateSlug = function (Set $set, Get $get, $record = null) {
         if ($record) {
            return;
         }

         $companyId = $get('company_id');
         $formTemplateId = $get('form_template_id');
         $title = $get('title');

         if (!$companyId || !$formTemplateId || !$title) {
            $set('slug', '');
            return;
         }

         $formTemplate = \App\Models\FormTemplate::find($formTemplateId);
         $templateName = $formTemplate ? $formTemplate->name : '';

         $slugBase = $title;
         if ($templateName) {
            $slugBase = $title;
         }

         $baseSlug = Str::slug($slugBase);

         $existingSlug = CompanyForm::where('slug', $baseSlug)
            ->where('company_id', $companyId)
            ->when($record, fn($query) => $query->where('id', '!=', $record->id))
            ->exists();

         if ($existingSlug) {
            $baseSlug .= '-' . substr(uniqid(), -5);
         }

         $set('slug', $baseSlug);
      };

      return [
         Select::make('company_id')
            ->label('Empresa')
            ->placeholder('Selecione uma empresa')
            ->relationship(
               'company',
               'name',
               modifyQueryUsing: fn(Builder $query) => $query->where('is_active', true)->orderBy('name')
            )
            ->searchable()
            ->preload()
            ->required()
            ->live()
            ->helperText(
               $isEdit
                  ? 'Alterar a empresa transferirá este formulário para outra organização.'
                  : 'Escolha a empresa que utilizará este formulário.'
            )
            ->afterStateUpdated(function (Set $set, $state, Get $get, $record) use ($generateSlug) {
               $generateSlug($set, $get, $record);
            }),

         Select::make('form_template_id')
            ->label('Modelo de Formulário')
            ->placeholder('Selecione um modelo')
            ->relationship(
               'formTemplate',
               'name',
               modifyQueryUsing: fn(Builder $query) => $query->where('is_vacancy_form', false)->orderBy('name')
            )
            ->searchable()
            ->preload()
            ->required()
            ->live()
            ->helperText(
               $isEdit
                  ? 'Atenção: Alterar o modelo pode afetar respostas existentes.'
                  : 'Selecione qual modelo de formulário será usado como base.'
            )
            ->afterStateUpdated(function (Set $set, $state, Get $get, $record) use ($generateSlug) {
               $generateSlug($set, $get, $record);
            }),

         TextInput::make('title')
            ->label('Título para este Vínculo')
            ->placeholder('Ex: Avaliação de Desempenho - Q3 2025')
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->helperText('Este título será exibido para os usuários que acessarem o formulário.')
            ->afterStateUpdated(function (Set $set, $state, Get $get, $record) use ($generateSlug) {
               $generateSlug($set, $get, $record);
            }),

         TextInput::make('slug')
            ->helperText('URL amigável para acessar o formulário. Será gerada automaticamente baseada no título.')
            ->prefix(function (Get $get) {
               $companyId = $get('company_id');
               return $companyId . '/';
            })
            ->required()
            ->unique(
               table: CompanyForm::class,
               column: 'slug',
               ignoreRecord: true,
               modifyRuleUsing: function (Unique $rule, Get $get) {
                  $companyId = $get('company_id');
                  return $rule->where('company_id', $companyId);
               }
            ),

         Textarea::make('description')
            ->label('Descrição do formulário')
            ->helperText('Esta descrição será exibida os usuários que acessarem o formulário.')
            ->rows(2)
      ];
   }

   protected function getTableQuery(): Builder
   {
      return CompanyForm::query()->with('company', 'formTemplate')->withCount('formResponses');
   }
}

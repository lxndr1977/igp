<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\CompanyForm;
use Illuminate\Support\Str;
use App\Models\FormTemplate;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\IconSize;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Unique;
use App\Filament\Pages\AllFormResponses;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;

class FormTemplatesRelationManager extends RelationManager
{
   protected static string $relationship = 'formTemplates';

   protected static ?string $title = 'Formulários';

   protected static string | BackedEnum | null $icon = 'heroicon-o-document-text';

   public function form(Schema $schema): Schema
   {
      $generateSlug = function (Set $set, Get $get, $record = null) {
         if ($record) return;

         $companyId = $get('company_id');
         $formTemplateId = $get('form_template_id');
         $title = $get('title');

         if (!$companyId || !$formTemplateId || !$title) {
            $set('slug', '');
            return;
         }

         $slugBase = $title;
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

      return $schema
         ->components([
            Select::make('company_id')
               ->label('Empresa')
               ->relationship('company', 'name', modifyQueryUsing: fn($query) => $query->where('is_active', true)->orderBy('name'))
               ->label('Empresa')
               ->default(fn() => $this->ownerRecord->id) // pega a empresa do record pai
               ->disabled() // não exibe para o usuário
               ->required(),

            Select::make('form_template_id')
               ->label('Modelo de Formulário')
               ->relationship('formTemplate', 'name', modifyQueryUsing: fn($query) => $query->where('is_vacancy_form', false)->orderBy('name'))
               ->searchable()
               ->preload()
               ->required()
               ->live()
               ->disabled(fn(Get $get, $record) => $record && $record->formResponses()->exists())
               ->helperText(
                  fn(Get $get, $record) =>
                  $record && $record->formResponses()->exists()
                     ? 'Não é possível alterar o modelo pois já existem respostas vinculadas.'
                     : 'Escolha o modelo de formulário.'
               )
               ->afterStateUpdated(function (Set $set, $state, Get $get, $record) use ($generateSlug) {
                  $generateSlug($set, $get, $record);
               }),

            TextInput::make('title')
               ->label('Título')
               ->helperText('Este título será exibido para os usuários que acessarem o formulário.')
               ->required()
               ->maxLength(255)
               ->columnSpanFull()
               ->live(onBlur: true)
               ->afterStateUpdated(function (Set $set, $state, Get $get, $record) use ($generateSlug) {
                  $generateSlug($set, $get, $record);
               }),

            TextInput::make('slug')
               ->label('Slug')
               ->helperText('URL amigável, gerada automaticamente a partir do título.')
               ->columnSpanFull()
               ->prefix(fn(Get $get) => $get('company_id') . '/')
               ->required()
               ->unique(
                  table: CompanyForm::class,
                  column: 'slug',
                  ignoreRecord: true,
                  modifyRuleUsing: fn(Unique $rule, Get $get) => $rule->where('company_id', $get('company_id'))
               ),

            Textarea::make('description')->columnSpanFull()
               ->label('Descrição')
               ->helperText('Esta descrição será exibida os usuários que acessarem o formulário.'),

            Toggle::make('is_active')
               ->required()
               ->default(true)
               ->label('Ativo')
               ->helperText('Se desativado, o formulário não poderá mais ser acessado pelos usuários. ')
               ->columnSpanFull(),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('slug')
         ->modifyQueryUsing(fn($query) => $query->withCompanyForm())
         ->defaultSort('created_at', 'desc')
         ->modelLabel('Formulário')
         ->pluralModelLabel('Formulários')
         ->heading('Formulários')
         ->description('Gerencie os modelos de formulários vinculados à empresa')
         ->emptyStateHeading('Nenhum formulário cadastrado')
         ->emptyStateDescription('Comece criando um formulário para uma empresa.')
         ->emptyStateIcon('heroicon-o-document-text')
         ->columns([
            TextColumn::make('formTemplate.name')
               ->label('Formulário')
               ->searchable()
               ->sortable(),

            TextColumn::make('slug')
               ->label('URL')
               ->formatStateUsing(fn($record) => "/{$record->slug}")
               ->copyable()
               ->copyableState(fn($record) => url("formularios/{$record->company_id}/{$record->slug}"))
               ->copyMessage('Copiado')
               ->searchable(),

            TextColumn::make('form_responses_count')
               ->label('Respostas')
               ->counts('formResponses')
               ->alignment(Alignment::Center)
               ->badge(),

            TextColumn::make('status')
               ->label('Status')
               ->badge()
               ->getStateUsing(fn($record) => $record->is_active ? 'Ativo' : 'Inativo')
               ->colors([
                  'success' => fn($state) => $state === 'Ativo',
                  'danger' => fn($state) => $state === 'Inativo',
               ]),

            TextColumn::make('created_at')
               ->label('Criado em')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
               ->label('Atualizado em')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true)
         ])
         ->filters([
            SelectFilter::make('is_active')
               ->label('Status')
               ->options([
                  1 => 'Ativo',
                  0 => 'Inativo'
               ])->default(true)
         ])
         ->headerActions([
            CreateAction::make(),
         ])
         ->recordActions([

            Action::make('viewResponses')
               ->label('Respostas')
               ->icon('heroicon-o-eye')
               ->url(
                  fn(CompanyForm $record) =>
                  "/painel-igp/all-form-responses/form/{$record->id}"
               ),

            ActionGroup::make([
               EditAction::make(),

               DeleteAction::make()
                  ->label('Excluir')
                  ->icon('heroicon-o-trash')
                  ->color('danger')
                  ->tooltip('Remover este vínculo permanentemente')
                  ->requiresConfirmation()
                  ->modalHeading('Excluir Empresa')
                  ->modalDescription('Tem certeza que deseja excluir este formulário? Esta ação não pode ser desfeita.')
                  ->modalSubmitActionLabel('Sim, Excluir')
                  ->modalCancelActionLabel('Cancelar')
                  ->successNotificationTitle(null)
                  ->action(function (CompanyForm $record): void {
                     try {
                        $record->delete();

                        Notification::make()
                           ->title('Formulário excluído!')
                           ->success()
                           ->send();
                     } catch (\Exception $e) {
                        Notification::make()
                           ->title('Não foi possível excluir')
                           ->body($e->getMessage())
                           ->danger()
                           ->send();
                     }
                  })
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

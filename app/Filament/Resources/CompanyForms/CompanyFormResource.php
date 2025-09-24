<?php

namespace App\Filament\Resources\CompanyForms;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\CompanyForm;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Support\Enums\Size;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\CompanyForms\Pages\ManageCompanyForms;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;

class CompanyFormResource extends Resource
{
   protected static ?string $model = CompanyForm::class;

   protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentText;

   protected static ?string $recordTitleAttribute = 'title';

   protected static bool $hasTitleCaseModelLabel = false;

   protected static ?string $label = 'Formulário';

   protected static ?string $pluralLabel = 'Formulários';


   public static function form(Schema $schema): Schema
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
               ->searchable()
               ->preload()
               ->required()
               ->reactive()
               ->disabled(fn(Get $get, $record) => $record && $record->formResponses()->exists())
               ->helperText(
                  fn(Get $get, $record) =>
                  $record && $record->formResponses()->exists()
                     ? 'Não é possível alterar a empresa pois já existem respostas vinculadas.'
                     : 'Escolha a empresa que utilizará este formulário.'
               )
               ->live()
               ->afterStateUpdated(function (Set $set, $state, Get $get, $record) use ($generateSlug) {
                  $generateSlug($set, $get, $record);
               }),

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
               ->label('Título para este Vínculo')
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

   public static function infolist(Schema $schema): Schema
   {
      return $schema
         ->components([
            TextEntry::make('title')
               ->weight(FontWeight::Bold)
               ->label('Título do Formulário'),

            TextEntry::make('description')
               ->placeholder('-')
               ->columnSpanFull()
               ->weight(FontWeight::Bold),

            TextEntry::make('company.name')
               ->label('Empresa')
               ->weight(FontWeight::Bold),

            TextEntry::make('formTemplate.name')
               ->label('Modelo de Formulário')
               ->weight(FontWeight::Bold),

            TextEntry::make('slug')
               ->label('URL amigável')
               ->copyable()
               ->copyableState(fn($record) => url("formularios/{$record->company_id}/{$record->slug}"))
               ->copyMessage('Copiado')
               ->weight(FontWeight::Bold),

            IconEntry::make('is_active')
               ->label('Formulário ativo?')
               ->boolean(),

            TextEntry::make('created_at')
               ->label('Criado em')
               ->dateTime()
               ->placeholder('-')
               ->weight(FontWeight::Bold),

            TextEntry::make('updated_at')
               ->label('Atualizado em')
               ->dateTime()
               ->placeholder('-')
               ->weight(FontWeight::Bold),

         ]);
   }

   public static function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('title')
         ->defaultSort('created_at', 'desc')
         ->heading('Listagem de Formulários')
         ->description('Gerencie os formulários criados para as empresas')
         ->emptyStateHeading('Nenhum formulário cadastrado')
         ->emptyStateDescription('Comece adicionando um modelo de formulário a uma empresa.')
         ->emptyStateIcon('heroicon-o-document-plus')
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
               ->toggleable(isToggledHiddenByDefault: true),
         ])
         ->filters([

            SelectFilter::make('company_id')
               ->label('Empresa')
               ->relationship('company', 'name', modifyQueryUsing: fn($query) => $query->where('is_active', true)->orderBy('name'))
               ->searchable()
               ->preload()
               ->indicator('Empresa')
               ->placeholder('Todas as empresas'),

            SelectFilter::make('is_active')
               ->label('Status')
               ->options([
                  1 => 'Ativo',
                  0 => 'Inativo'
               ])
         ])
         ->recordActions([
            Action::make('viewResponses')
               ->label('Respostas')
               ->icon('heroicon-o-document-text')
               ->iconSize(IconSize::Small)
               ->size(Size::Small)
               ->tooltip('Visualizar todas as respostas deste formulário')
               ->url(fn(CompanyForm $record) => route('filament.pages.all-form-responses', ['formId' => $record->id])),

            ActionGroup::make([
               ViewAction::make(),

               EditAction::make()
                  ->color('gray'),

               DeleteAction::make()
                  ->label('Excluir')
                  ->icon('heroicon-o-trash')
                  ->color('danger')
                  ->tooltip('Remover este vínculo permanentemente')
                  ->requiresConfirmation()
                  ->modalHeading('Excluir Formulário')
                  ->modalDescription('Tem certeza que deseja excluir este formulário? Esta ação não pode ser desfeita.')
                  ->modalSubmitActionLabel('Sim, Excluir')
                  ->modalCancelActionLabel('Cancelar')
                  ->successNotificationTitle(null)
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

   public static function getPages(): array
   {
      return [
         'index' => ManageCompanyForms::route('/'),
      ];
   }
}

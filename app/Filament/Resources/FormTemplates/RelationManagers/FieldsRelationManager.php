<?php

namespace App\Filament\Resources\FormTemplates\RelationManagers;

use App\Enums\FormFieldType;
use App\Enums\FormFieldTypeEnum;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Enums\IconSize;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;

class FieldsRelationManager extends RelationManager
{
   protected static string $relationship = 'fields';

   protected static ?string $title = 'Campos';

   protected static string | BackedEnum | null $icon = 'heroicon-o-queue-list';

   public function form(Schema $schema): Schema
   {
      return $schema
         ->components([
            Section::make('Informações Básicas')
               ->columnSpanFull()
               ->schema([
                  Grid::make(2)
                     ->schema([
                        Select::make('form_template_section_id')
                           ->label('Seção do Formulário')
                           ->options(function () {
                              return $this->getOwnerRecord()
                                 ->sections()
                                 ->pluck('title', 'id');
                           })
                           ->required(),

                        Select::make('field_type')
                           ->label('Tipo do Campo')
                           ->options(FormFieldTypeEnum::class)
                           ->required()
                           ->live()
                           ->afterStateUpdated(fn(Set $set) => $set('options', null)),
                     ]),

                  Grid::make(2)
                     ->schema([
                        TextInput::make('label')
                           ->label('Rótulo do Campo')
                           ->required()
                           ->maxLength(255),

                        TextInput::make('placeholder')
                           ->label('Placeholder')
                           ->maxLength(255)
                           ->hidden(fn(Get $get) => in_array($get('field_type'), [FormFieldTypeEnum::Radio, 'checkbox', 'rating', FormFieldTypeEnum::Scale])),
                     ]),

                  Textarea::make('help_text')
                     ->label('Texto de Ajuda')
                     ->rows(2)
                     ->columnSpanFull(),

                  Grid::make(2)
                     ->schema([
                        Toggle::make('is_required')
                           ->label('Campo Obrigatório'),


                        Toggle::make('is_active')
                           ->label('Ativo')
                           ->default(true),
                     ]),
               ]),

            Section::make('Opções do Campo')
               ->columnSpanFull()
               ->schema([
                  Repeater::make('options')
                     ->label('Opções')
                     ->simple(
                        TextInput::make('label')
                           ->label('Rótulo')
                           ->required(),
                     )
                     ->visible(fn(Get $get) => in_array($get('field_type'), [FormFieldTypeEnum::SelectSingle, FormFieldTypeEnum::SelectMultiple, FormFieldTypeEnum::Radio]))
                     ->columnSpanFull()
                     ->minItems(1)
                     ->addActionLabel('Adicionar Opção'),
               ])
               ->visible(fn(Get $get) => in_array($get('field_type'), [FormFieldTypeEnum::SelectSingle, FormFieldTypeEnum::SelectMultiple, FormFieldTypeEnum::Radio])),

            Section::make('Configurações de Avaliação')
               ->columnSpanFull()
               ->schema([
                  Grid::make(2)
                     ->schema([
                        TextInput::make('field_config.max_rating')
                           ->label('Máximo de Estrelas')
                           ->numeric()
                           ->default(5)
                           ->minValue(1)
                           ->maxValue(10),

                        Toggle::make('field_config.show_labels')
                           ->label('Mostrar Rótulos')
                           ->default(false),
                     ]),

                  Textarea::make('field_config.labels')
                     ->label('Rótulos das Estrelas (separados por vírgula)')
                     ->placeholder('Péssimo, Ruim, Regular, Bom, Excelente')
                     ->visible(fn(Get $get) => $get('field_config.show_labels')),
               ])
               ->visible(fn(Get $get) => $get('field_type') === 'rating'),

            Section::make('Configurações de Escala')
               ->columnSpanFull()
               ->schema([
                  Grid::make(3)
                     ->schema([
                        TextInput::make('field_config.min_value')
                           ->label('Valor Mínimo')
                           ->numeric()
                           ->default(1),

                        TextInput::make('field_config.max_value')
                           ->label('Valor Máximo')
                           ->numeric()
                           ->default(10),

                        TextInput::make('field_config.step')
                           ->label('Incremento')
                           ->numeric()
                           ->default(1),
                     ]),

                  Grid::make(2)
                     ->schema([
                        TextInput::make('field_config.min_label')
                           ->label('Rótulo Mínimo')
                           ->placeholder('Discordo totalmente'),

                        TextInput::make('field_config.max_label')
                           ->label('Rótulo Máximo')
                           ->placeholder('Concordo totalmente'),
                     ]),

                  Toggle::make('field_config.show_values')
                     ->label('Mostrar Valores Numéricos')
                     ->default(true),
               ])
               ->visible(fn(Get $get) => $get('field_type') === FormFieldTypeEnum::Scale),

            Section::make('Validações Adicionais')
               ->columnSpanFull()
               ->schema([
                  Grid::make(2)
                     ->schema([
                        TextInput::make('field_config.min_length')
                           ->label('Comprimento Mínimo')
                           ->numeric()
                           ->minValue(0)
                           ->helperText('Número mínimo de caracteres'),

                        TextInput::make('field_config.max_length')
                           ->label('Comprimento Máximo')
                           ->numeric()
                           ->minValue(1)
                           ->helperText('Número máximo de caracteres'),
                     ])
                     ->visible(fn(Get $get) => in_array($get('field_type'), [
                        FormFieldTypeEnum::Text,
                        FormFieldTypeEnum::Textarea,
                        FormFieldTypeEnum::Email,
                        FormFieldTypeEnum::Tel,
                     ])),

                  Grid::make(2)
                     ->schema([
                        TextInput::make('field_config.min_value')
                           ->label('Valor Mínimo')
                           ->numeric()
                           ->helperText('Valor numérico mínimo permitido'),

                        TextInput::make('field_config.max_value')
                           ->label('Valor Máximo')
                           ->numeric()
                           ->helperText('Valor numérico máximo permitido'),
                     ])
                     ->visible(fn(Get $get) => $get('field_type') === FormFieldTypeEnum::Number),

                  Select::make('field_config.validation_pattern')
                     ->label('Padrão de Validação')
                     ->options([
                        'cpf' => 'CPF',
                        'cnpj' => 'CNPJ',
                        'cep' => 'CEP',
                        'phone_br' => 'Telefone Brasileiro',
                        'custom' => 'Personalizado (Regex)',
                     ])
                     ->live()
                     ->visible(fn(Get $get) => in_array($get('field_type'), [
                        FormFieldTypeEnum::Text,
                        FormFieldTypeEnum::Tel,
                     ])),

                  TextInput::make('field_config.custom_regex')
                     ->label('Expressão Regular Personalizada')
                     ->placeholder('/^[A-Za-z\s]+$/')
                     ->helperText('Apenas para usuários avançados. Ex: /^[A-Za-z\s]+$/ para apenas letras')
                     ->visible(fn(Get $get) => $get('field_config.validation_pattern') === 'custom'),

                  TextInput::make('field_config.validation_message')
                     ->label('Mensagem de Erro Personalizada')
                     ->placeholder('Por favor, insira um valor válido')
                     ->helperText('Mensagem exibida quando a validação falha')
                     ->columnSpanFull(),
               ])
               ->collapsible()
               ->collapsed()
               ->description('Configure validações adicionais para este campo'),
         ]);
   }

   public function table(Table $table): Table
   {
      return $table
         ->recordTitleAttribute('label')
         ->recordTitle('Campo')
         ->modelLabel('Campo')
         ->pluralModelLabel('Campos')
         ->heading('Campos do formulário')
         ->description('Gerencie os campos do formulário')
         ->reorderable('order')
         ->reorderRecordsTriggerAction(
            fn(Action $action, bool $isReordering) => $action
               ->button()
               ->label($isReordering ? 'Disable reordering' : 'Enable reordering'),
         )
         ->columns([
            TextColumn::make('section.title')
               ->label('Seção'),
            TextColumn::make('label')
               ->label('Rótulo'),
            TextColumn::make('field_type')
               ->label('Tipo'),
            IconColumn::make('is_required')
               ->label('Obrigatório')
               ->boolean()
               ->size(IconSize::Small),
            TextColumn::make('created_at')
               ->label('Criado')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
               ->label('Atualizado')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),
         ])
         ->filters([
            //
         ])
         ->headerActions([
            CreateAction::make(),
         ])
         ->recordActions([
            EditAction::make(),
            DeleteAction::make(),
         ])
         ->toolbarActions([
            BulkActionGroup::make([
               DeleteBulkAction::make(),
            ]),
         ]);
   }
}

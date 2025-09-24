<?php

namespace App\Filament\Resources\FormTemplates;

use App\Filament\Resources\FormTemplates\Pages\CreateFormTemplate;
use App\Filament\Resources\FormTemplates\Pages\EditFormTemplate;
use App\Filament\Resources\FormTemplates\Pages\ListFormTemplates;
use App\Filament\Resources\FormTemplates\RelationManagers\FieldsRelationManager;
use App\Filament\Resources\FormTemplates\RelationManagers\SectionsRelationManager;
use App\Filament\Resources\FormTemplates\Schemas\FormTemplateForm;
use App\Filament\Resources\FormTemplates\Tables\FormTemplatesTable;
use App\Models\FormTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FormTemplateResource extends Resource
{
   protected static ?string $model = FormTemplate::class;

   protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

   protected static ?string $recordTitleAttribute = 'name';

   protected static ?string $label = 'Modelo Formulário';

   protected static ?string $pluralLabel = 'Modelos de Formulários';

   protected static bool $hasTitleCaseModelLabel = false;

   public static function form(Schema $schema): Schema
   {
      return FormTemplateForm::configure($schema);
   }

   public static function table(Table $table): Table
   {
      return FormTemplatesTable::configure($table);
   }

   public static function getRelations(): array
   {
      return [
         SectionsRelationManager::class,
         FieldsRelationManager::class,
      ];
   }

   public static function getPages(): array
   {
      return [
         'index' => ListFormTemplates::route('/'),
         'create' => CreateFormTemplate::route('/create'),
         'edit' => EditFormTemplate::route('/{record}/edit'),
      ];
   }
}

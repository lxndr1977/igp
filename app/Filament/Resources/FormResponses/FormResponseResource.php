<?php

namespace App\Filament\Resources\FormResponses;

use App\Filament\Resources\FormResponses\Pages\CreateFormResponse;
use App\Filament\Resources\FormResponses\Pages\EditFormResponse;
use App\Filament\Resources\FormResponses\Pages\ListFormResponses;
use App\Filament\Resources\FormResponses\Schemas\FormResponseForm;
use App\Filament\Resources\FormResponses\Tables\FormResponsesTable;
use App\Models\FormResponse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FormResponseResource extends Resource
{
   protected static ?string $model = FormResponse::class;

   protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

   protected static bool $hasTitleCaseModelLabel = false;

   protected static ?string $recordTitleAttribute = 'subject_id';

   protected static ?string $title = 'Currículos';

   protected static ?string $label = 'Currículo';

   protected static ?string $pluralLabel = 'Currículos';

   public static function form(Schema $schema): Schema
   {
      return FormResponseForm::configure($schema);
   }

   public static function table(Table $table): Table
   {
      return FormResponsesTable::configure($table);
   }

   public static function getRelations(): array
   {
      return [
         //
      ];
   }

   public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
   {
      return parent::getEloquentQuery()
         ->where('subject_type', \App\Models\JobVacancy::class)
         ->orderBy('submitted_at', 'desc');
   }

   public static function getPages(): array
   {
      return [
         'index' => ListFormResponses::route('/'),
      ];
   }
}

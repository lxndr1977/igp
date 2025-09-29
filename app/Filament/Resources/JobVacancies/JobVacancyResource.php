<?php

namespace App\Filament\Resources\JobVacancies;

use App\Filament\Resources\JobVacancies\Pages\CreateJobVacancy;
use App\Filament\Resources\JobVacancies\Pages\EditJobVacancy;
use App\Filament\Resources\JobVacancies\Pages\JobVacancyResponses;
use App\Filament\Resources\JobVacancies\Pages\ListJobVacancies;
use App\Filament\Resources\JobVacancies\RelationManagers\FormResponsesRelationManager;
use App\Filament\Resources\JobVacancies\Schemas\JobVacancyForm;
use App\Filament\Resources\JobVacancies\Tables\JobVacanciesTable;
use App\Models\JobVacancy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobVacancyResource extends Resource
{
   protected static ?string $model = JobVacancy::class;

   protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

   protected static ?string $recordTitleAttribute = 'title';

   protected static bool $hasTitleCaseModelLabel = false;

   protected static ?string $label = 'Vaga de Emprego';

   protected static ?string $pluralLabel = 'Vagas de Emprego';

   public static function form(Schema $schema): Schema
   {
      return JobVacancyForm::configure($schema);
   }

   public static function table(Table $table): Table
   {
      return JobVacanciesTable::configure($table);
   }

   public static function getRelations(): array
   {
      return [
         FormResponsesRelationManager::class,
      ];
   }

   public static function getPages(): array
   {
      return [
         'index' => ListJobVacancies::route('/'),
         'create' => CreateJobVacancy::route('/create'),
         'edit' => EditJobVacancy::route('/{record}/edit'),
         'responses' => Pages\JobVacancyResponses::route('/{record}/responses/{response}'),
      ];
   }
}

<?php

namespace App\Filament\Resources\Companies;

use BackedEnum;
use App\Models\Company;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Resources\Companies\Tables\CompaniesTable;
use App\Filament\Resources\Companies\RelationManagers\ContactsRelationManager;
use App\Filament\Resources\Companies\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\Companies\RelationManagers\FormTemplatesRelationManager;
use App\Filament\Resources\Companies\RelationManagers\JobVacanciesRelationManager;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Empresa';
    
    protected static ?string $pluralLabel = 'Empresas';

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompaniesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            ContactsRelationManager::class,
            FormTemplatesRelationManager::class,
            JobVacanciesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
}

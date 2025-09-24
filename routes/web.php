<?php

use Livewire\Volt\Volt;
use App\Livewire\Site\JobVacancy;
use App\Livewire\Site\CompanyForm;
use Illuminate\Support\Facades\Route;
use App\Livewire\Site\CompanyFormPage;
use App\Filament\Pages\AllJobResponses;
use App\Filament\Pages\AllFormResponses;
use App\Filament\Pages\FormResponseDetails;
use App\Http\Controllers\Site\HomeController;


Route::view('dashboard', 'dashboard')
   ->middleware(['auth', 'verified'])
   ->name('dashboard');

Route::middleware(['auth'])->group(function () {
   Route::redirect('settings', 'settings/profile');

   Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
   Volt::route('settings/password', 'settings.password')->name('settings.password');
   Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/formularios/{companyId}/{formSlug}', CompanyFormPage::class)
   ->defaults('type',  'jobs')
->name('company.form');

Route::get('/vagas/{companyId}/{formSlug}', CompanyFormPage::class)
   ->defaults('type',  'vagas')
   ->name('company.form.vacancy');
// Route::get('/formulario/{slug}', CompanyForm::class)->name('company.form');

Route::get('/painel-igp/form-response-details/{templateId}/{responseId}', FormResponseDetails::class)->name('form-response-details');

Route::get('/painel-igp/all-form-responses/form/{formId}', AllFormResponses::class)
   ->name('filament.pages.all-form-responses');

Route::get('/painel-igp/all-job-responses/form/{formId}', AllJobResponses::class)
   ->name('filament.pages.all-job-responses');


Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::view('/sobre', 'site.pages.about')->name('site.about');

Route::prefix('servicos')->name('site.services')->group(function () {
    Route::view('/', 'site.pages.services')->name('');
    Route::view('/consultoria', 'site.pages.services.consulting')->name('.consulting');
    Route::view('/nr-1', 'site.pages.services.nr-1')->name('.nr-1');
    Route::view('/palestras', 'site.pages.services.talks')->name('.talks');
    Route::view('/treinamentos', 'site.pages.services.trainings')->name('.trainings');
    Route::view('/recrutamento', 'site.pages.services.recruitment')->name('.recruitment');
});

Route::prefix('treinamentos')->name('site.trainings')->group(function () {
   Route::view('/', 'site.pages.trainings')->name('');
   Route::view('/nr-1', 'site.pages.trainings.nr-1')->name('.nr-1');
   Route::view('/escuta-e-acolhimento', 'site.pages.trainings.employee-listing')->name('.employee-listing');
});

Route::get('/vagas', JobVacancy::class)->name('site.job-vacancies');
Route::view('/contato', 'site.pages.contact')->name('site.contact');
Route::view('/politica-de-privacidade', 'site.pages.contact')->name('site.privacy-policy');

require __DIR__ . '/auth.php';

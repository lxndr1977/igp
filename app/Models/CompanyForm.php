<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CompanyForm extends Model
{
   protected $fillable = [
      'company_id',
      'form_template_id',
      'title',
      'description',
      'slug',
      'is_active'
   ];

   protected $casts = [
      'is_active' => 'boolean',
   ];

   // Relacionamentos diretos (corretos)
   public function company(): BelongsTo
   {
      return $this->belongsTo(Company::class);
   }

   public function formTemplate(): BelongsTo
   {
      return $this->belongsTo(FormTemplate::class);
   }

   public function formResponses(): MorphMany
   {
      return $this->morphMany(FormResponse::class, 'subject');
   }

   // Relacionamentos através do FormTemplate (corretos)
   public function sections(): HasManyThrough
   {
      return $this->hasManyThrough(
         FormTemplateSection::class,
         FormTemplate::class,
         'id', // Foreign key no FormTemplate
         'form_template_id', // Foreign key no FormTemplateSection
         'form_template_id', // Local key no CompanyForm
         'id' // Local key no FormTemplate
      )->orderBy('order');
   }

   public function activeSections(): HasManyThrough
   {
      return $this->hasManyThrough(
         FormTemplateSection::class,
         FormTemplate::class,
         'id',
         'form_template_id',
         'form_template_id',
         'id'
      )->where('form_template_sections.is_active', true)->orderBy('order');
   }

   public function formFields(): HasManyThrough
   {
      return $this->hasManyThrough(
         FormTemplateField::class,
         FormTemplateSection::class,
         'form_template_id', // Foreign key no FormTemplateSection
         'form_template_section_id', // Foreign key no FormTemplateField
         'form_template_id', // Local key no CompanyForm
         'id' // Local key no FormTemplateSection
      )->orderBy('order');
   }

   public function activeFormFields(): HasManyThrough
   {
      return $this->hasManyThrough(
         FormTemplateField::class,
         FormTemplateSection::class,
         'form_template_id',
         'form_template_section_id',
         'form_template_id',
         'id'
      )->where('form_template_fields.is_active', true)->orderBy('form_template_fields.order');
   }

   /**
    * Campos organizados por seções (para renderização do formulário público)
    */
   public function fieldsGroupedBySections()
   {
      return $this->activeSections()->with(['activeFields'])->get();
   }

   /**
    * Todos os campos organizados (seções + órfãos)
    */
   public function getOrganizedFields()
   {
      $sections = $this->fieldsGroupedBySections();

      return [
         'sections' => $sections
      ];
   }

   /**
    * Scope para formulários ativos
    */
   public function scopeActive($query)
   {
      return $query->where('is_active', true);
   }

   /**
    * Encontrar formulário por slug
    */
   public function scopeBySlug($query, $slug)
   {
      return $query->where('slug', $slug);
   }

   /**
    * Verifica se o formulário tem seções definidas
    */
   public function hasSections(): bool
   {
      return $this->sections()->exists();
   }

   /**
    * Total de campos no formulário
    */
   public function getTotalFieldsAttribute(): int
   {
      return $this->formFields()->count();
   }

   /**
    * Total de seções no formulário
    */
   public function getTotalSectionsAttribute(): int
   {
      return $this->sections()->count();
   }

   /**
    * Acesso direto ao FormTemplate para propriedades como collect_email, etc.
    */
   public function getCollectEmailAttribute(): bool
   {
      return $this->formTemplate->collect_email ?? false;
   }

   public function getCollectNameAttribute(): bool
   {
      return $this->formTemplate->collect_name ?? false;
   }

   public function getSuccessMessageAttribute(): ?string
   {
      return $this->formTemplate->success_message;
   }

   public function getRedirectUrlAttribute(): ?string
   {
      return $this->formTemplate->redirect_url;
   }

   #[Scope]
   protected function withCompanyForm(Builder $query): void
   {
      $query->whereHas('formTemplate', function ($subQuery) {
         $subQuery->where('is_vacancy_form', false);
      });
   }

   protected static function booted()
   {
      static::deleting(function ($companyForm) {
         if ($companyForm->formResponses()->exists()) {
            throw new \Exception('Este formulário não pode ser excluído porque possui respostas.');
         }
      });
   }
}

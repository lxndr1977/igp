<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormTemplate extends Model
{
   protected $fillable = [
      'name',
      'description',
      'is_active',
      'is_vacancy_form',
      'collect_email',
      'collect_name',
      'title_success_message',
      'success_message',
      'redirect_url',
   ];

   protected $casts = [
      'is_active' => 'boolean',
      'is_vacancy_form' => 'boolean',
      'collect_email' => 'boolean',
      'collect_name' => 'boolean',
   ];

   public function companies()
   {
      return $this->belongsToMany(Company::class, 'company_form_templates')
         ->withPivot(['slug', 'is_active'])
         ->withTimestamps();
   }

   public function jobVacancies(): HasMany
   {
      return $this->hasMany(JobVacancy::class);
   }

   public function companyForms(): HasMany
   {
      return $this->hasMany(CompanyForm::class);
   }

   public function sections()
   {
      return $this->hasMany(FormTemplateSection::class)
         ->orderBy('order');
   }

   public function fields()
   {
      return $this->hasManyThrough(
         FormTemplateField::class,       // modelo final
         FormTemplateSection::class,     // modelo intermediário
         'form_template_id',             // chave FK em form_template_sections
         'form_template_section_id',     // chave FK em form_template_fields
         'id',                           // PK em form_templates
         'id'                            // PK em form_template_sections
      )->orderBy('order');
   }

   #[Scope]
   protected function vacancyForm(Builder $query): void
   {
      $query->where('is_vacancy_form', true);
   }

   #[Scope]
   protected function generalForm(Builder $query): void
   {
      $query->where('is_vacancy_form', false);
   }

   protected static function booted()
   {
      static::deleting(function ($formTemplate) {
         if ($formTemplate->companyForms()->exists()
            || $formTemplate->jobVacancies()->exists()) {
            throw new \Exception('Este modelo não pode ser excluído pois está relacionado a vagas ou formulários.');
         }
      });
   }
}

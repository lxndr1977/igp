<?php

namespace App\Models;

use App\Enums\WorkLocationEnum;
use App\Enums\EmploymentTypeEnum;
use App\Enums\JobVacancyStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class JobVacancy extends Model
{
   protected $fillable = [
      'company_id',
      'form_template_id',
      'title',
      'slug',
      'description',
      'requirements',
      'benefits',
      'employment_type',
      'work_location',
      'department',
      'level',
      'city',
      'state',
      'country',
      'salary_min',
      'salary_max',
      'show_salary',
      'show_company_name',
      'application_deadline',
      'status',
      'is_featured',
   ];

   protected $casts = [
      'show_salary' => 'boolean',
      'show_company_name' => 'boolean',
      'is_featured' => 'boolean',
      'salary_min' => 'decimal:2',
      'salary_max' => 'decimal:2',
      'application_deadline' => 'date',
      'employment_type' => EmploymentTypeEnum::class,
      'work_location' => WorkLocationEnum::class,
      'status' => JobVacancyStatusEnum::class,
   ];

   public function company(): BelongsTo
   {
      return $this->belongsTo(Company::class);
   }

   public function formResponses(): MorphMany
   {
      return $this->morphMany(FormResponse::class, 'subject');
   }

   public function formTemplate(): BelongsTo
   {
      return $this->belongsTo(FormTemplate::class);
   }

   public function scopeActive(Builder $query): Builder
   {
      return $query->where('status', 'active')
         ->where('status', JobVacancyStatusEnum::Active->value)
         ->where(function ($q) {
            $q->whereNull('application_deadline')
               ->orWhere('application_deadline', '>=', now());
         });
   }

   public function scopeFeatured(Builder $query): Builder
   {
      return $query->where('is_featured', true);
   }

   public function scopeByLocation(Builder $query, string $city, string $state): Builder
   {
      return $query->when($city, fn($q) => $q->where('city', $city))
         ->when($state, fn($q) => $q->where('state', $state));
   }

   public function scopeBySalaryRange(Builder $query, float $min, float $max): Builder
   {
      return $query->when($min, fn($q) => $q->where('salary_max', '>=', $min))
         ->when($max, fn($q) => $q->where('salary_min', '<=', $max));
   }

   public function getFormattedSalaryAttribute(): string
   {
      if (!$this->salary_min && !$this->salary_max || !$this->show_salary) {
         return 'A combinar';
      }

      $prefix = 'R$ ';

      if ($this->salary_min && $this->salary_max) {
         return "{$prefix}" . number_format($this->salary_min, 2, ',', '.') .
            " - {$prefix}" . number_format($this->salary_max, 2, ',', '.');
      }

      $salary = $this->salary_min ?: $this->salary_max;
      return "{$prefix}" . number_format($salary, 2, ',', '.');
   }

   public function getIsExpiredAttribute(): bool
   {
      return $this->application_deadline &&
         $this->application_deadline->isPast();
   }

   public function getCanReceiveApplicationsAttribute(): bool
   {
      return $this->status === JobVacancyStatusEnum::Active
         && $this->is_active
         && !$this->is_expired;
   }

   public function getHasFormTemplateAttribute(): bool
   {
      return $this->company_form_template_id !== null;
   }

   public function getWorkLocationLabelAttribute(): string
   {
      return  $this->work_location->getLabel();
   }

   public function getEmploymentTypeLabelAttribute(): string
   {
      return $this->employment_type->getLabel();
   }

   public function getCompanyNameAttribute(): string
   {
      return $this->show_company_name
         ? $this->company->name
         : 'Confidencial';
   }

   #[Scope]
   protected function withVacancyForm(Builder $query): void
   {
      // $query->whereHas('formTemplate', function ($subQuery) {
      //    $subQuery->where('is_vacancy_form', true);
      // });
      
   }
}

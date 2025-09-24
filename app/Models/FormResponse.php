<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormResponse extends Model
{
   protected $fillable = [
      'subject_id',
      'subject_type',
      'respondent_email',
      'respondent_name',
      'ip_address',
      'user_agent',
      'submitted_at',
   ];

   protected $casts = [
      'submitted_at' => 'datetime',
   ];

   protected static function boot()
   {
      parent::boot();

      static::creating(function ($response) {
         if (empty($response->submitted_at)) {
            $response->submitted_at = now();
         }
      });
   }

   public function subject(): MorphTo
   {
      return $this->morphTo();
   }

   public function companyFormTemplate(): BelongsTo
   {
      return $this->belongsTo(CompanyForm::class);
   }

   public function formFieldResponses(): HasMany
   {
      return $this->hasMany(FormFieldResponse::class);
   }

   public function scopeForForm($query, $formId)
   {
      return $query->where('form_id', $formId);
   }

   public function getFormattedResponses(): array
   {
      $responses = [];

      foreach ($this->formFieldResponses as $fieldResponse) {
         $responses[$fieldResponse->formField->label] = $fieldResponse->value;
      }

      return $responses;
   }

   public function getCompanyNameAttribute(): ?string
   {
      if ($this->subject_type === \App\Models\CompanyForm::class) {
         return $this->subject->company->name ?? null;
      }

      if ($this->subject_type === \App\Models\JobVacancy::class) {
         return $this->subject->company->name ?? null;
      }

      return null;
   }

   public function getFormTypeAttribute(): string
   {
      return class_basename($this->subject_type);
   }
}

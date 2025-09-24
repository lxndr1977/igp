<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormFieldResponse extends Model
{
   protected $fillable = [
      'form_response_id',
      'form_template_field_id',
      'value',
   ];

   protected $casts = [
      'value' => 'json',
   ];

   public function formResponse(): BelongsTo
   {
      return $this->belongsTo(FormResponse::class);
   }

   public function formTemplateField(): BelongsTo
   {
      return $this->belongsTo(FormTemplateField::class);
   }

   public function getFormattedValueAttribute()
   {
      if (is_array($this->value)) {
         return implode(', ', $this->value);
      }

      return $this->value;
   }

   public function scopeForField($query, $fieldId)
   {
      return $query->where('form_field_id', $fieldId);
   }
}

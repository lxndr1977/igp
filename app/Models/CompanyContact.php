<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CompanyContact extends Model
{
   protected $fillable = [
      'company_id',
      'name',
      'department',
      'email',
      'phone',
   ];

   public function company(): BelongsTo
   {
      return $this->belongsTo(Company::class);
   }

   protected function phone(): Attribute
   {
      return Attribute::make(
         get: fn($value) => $value ? $this->formatPhone($value) : $value,
         set: fn($value) => preg_replace('/\D/', '', $value),
      );
   }

   private function formatPhone($value)
   {
      $cleaned = preg_replace('/\D/', '', $value);

      if (strlen($cleaned) === 11) {
         return preg_replace('/(\d{2})(\d{1})(\d{4})(\d{4})/', '($1) $2$3-$4', $cleaned);
      } elseif (strlen($cleaned) === 10) {
         return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $cleaned);
      }

      return $cleaned;
   }
}

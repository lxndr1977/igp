<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAddress extends Model
{
   protected $fillable = [
      'company_id',
      'name',
      'street',
      'number',
      'complement',
      'district',
      'city',
      'state',
      'zip_code',
   ];

   public function company(): BelongsTo
   {
      return $this->belongsTo(Company::class);
   }

   protected function zipCode(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strlen($value) === 8
                ? substr($value, 0, 5) . '-' . substr($value, 5, 3)
                : $value,
            set: fn ($value) => str_replace('-', '', $value)
        );
    }
}

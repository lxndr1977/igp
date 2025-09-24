<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
   use HasFactory;
   
   protected $fillable = ['cnpj', 'name', 'is_active'];

   protected $casts = [
      'is_active' => 'boolean',
   ];

   public function addresses(): HasMany
   {
      return $this->hasMany(CompanyAddress::class);
   }

   public function contacts(): HasMany
   {
      return $this->hasMany(CompanyContact::class);
   }

   public function formTemplates(): HasMany
   {
      return $this->hasMany(CompanyForm::class);
   }

   public function jobVacancies(): HasMany
   {
      return $this->hasMany(JobVacancy::class, 'company_id', 'id');
   }
}

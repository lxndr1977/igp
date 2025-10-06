<?php

namespace App\Observers;

use App\Models\Company;
use Illuminate\Support\Facades\Cache;

class CompanyObserver
{
   public function updated(Company $company)
   {
      Cache::forget("company_{$company->id}");
   }
}

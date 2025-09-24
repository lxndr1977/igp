<?php

namespace App\Http\Controllers\Site;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
   public function index()
   {
      $featuredJobVacancies = JobVacancy::active()
         ->with(['company', 'formTemplate'])
         ->orderBy('is_featured', 'desc')
         ->orderBy('application_deadline', 'asc')
         ->limit(3)
         ->get();

      return view('site.pages.home', compact('featuredJobVacancies'));
   }
}

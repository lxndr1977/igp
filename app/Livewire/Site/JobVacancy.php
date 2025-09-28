<?php

namespace App\Livewire\Site;

use Livewire\Component;
use App\Models\JobVacancy as JobVacancyModel;

class JobVacancy extends Component
{
   public $search = '';
   public $city = '';
   public $state = '';
   public $jobVacancies = [];

   public function mount()
   {
      $this->filterJobs(); 
   }

   public function filterJobs()
   {
      $this->jobVacancies = JobVacancyModel::active()
         ->with(['company', 'formTemplate'])
         ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
         ->when($this->city, fn($q) => $q->where('city', $this->city))
         ->when($this->state, fn($q) => $q->where('state', $this->state))
         ->orderBy('is_featured', 'desc')
         ->orderBy('application_deadline', 'asc')
         ->get();
   }

   public function render()
   {
      return view('livewire.site.job-vacancy');
   }

   public function clearFilters()
   {
      $this->search = '';
      $this->city = '';
      $this->state = '';

      $this->filterJobs();
   }
}

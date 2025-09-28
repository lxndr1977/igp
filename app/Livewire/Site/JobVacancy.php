<?php

namespace App\Livewire\Site;

use App\Enums\BrazilianStateEnum;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JobVacancy as JobVacancyModel;

class JobVacancy extends Component
{
    use WithPagination;

<<<<<<< HEAD
   public function mount()
   {
      $this->filterJobs(); 
   }
=======
    public $search = '';
    public $city = '';
    public $state = '';
    public $statesList = [];
    public $totalCount; 
>>>>>>> feature/fix-job-vancy-info

    protected $updatesQueryString = ['search', 'city', 'state'];

    public function mount()
    {
        $this->statesList = BrazilianStateEnum::forMarySelect();

        $this->updateTotalCount();
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->updateTotalCount();
    }

    public function updatingCity()
    {
        $this->resetPage();
        $this->updateTotalCount();
    }

    public function updatingState()
    {
        $this->resetPage();
        $this->updateTotalCount(); 
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->updateTotalCount(); 
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->city = '';
        $this->state = '';
        $this->resetPage();
        $this->updateTotalCount(); 
    }

    private function updateTotalCount()
    {
        $this->totalCount = JobVacancyModel::active()
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->city, fn($q) => $q->where('city', $this->city))
            ->when($this->state, fn($q) => $q->where('state', $this->state))
            ->count();
    }

    public function render()
    {
        $jobVacancies = JobVacancyModel::active()
            ->with(['company', 'formTemplate'])
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->city, fn($q) => $q->where('city', $this->city))
            ->when($this->state, fn($q) => $q->where('state', $this->state))
            ->orderBy('is_featured', 'desc')
            ->orderBy('application_deadline', 'asc')
            ->simplePaginate(1);

        return view('livewire.site.job-vacancy', compact('jobVacancies'));
    }
}
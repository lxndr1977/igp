<div>
   <x-site.page-header
      title="Oportunidades de Emprego"
      subtitle="Envie o seu currículo"
      :breadcrumb="[['label' => 'Início', 'url' => route('site.home')], ['label' => 'Vagas']]" />

   <div class="bg-secondary-600 mx-auto px-4 sm:px-6 lg:px-8 pb-20">

      <!-- Filtros melhorados -->
      <div class="max-w-5xl mx-auto bg-white rounded-lg border border-neutral-200 p-4 mb-6">
         <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
               <input type="text" placeholder="Buscar por palavra-chave..."
                  wire:model="search"
                  class="border border-neutral-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>

            <div class="flex-1 md:flex-none md:w-1/4">
               <input type="text" placeholder="Cidade"
                  wire:model="city"
                  class="border border-neutral-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>

            <div class="flex-1 md:flex-none md:w-1/4">
               <input type="text" placeholder="Estado"
                  wire:model="state"
                  class="border border-neutral-300 rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
            </div>

            <x-mary-button label="Filtrar" wire:click="filterJobs"
               icon="o-magnifying-glass"
               class="btn-primary" />

            <x-mary-button label="Limpar" wire:click="clearFilters"
               icon="o-x-mark"
               class="btn-outline" />
         </div>
      </div>
   </div>
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-20">

      <div class="mb-6">
         @if ($jobVacancies->isNotEmpty())
            <div class="mt-3 pt-3">
               <p class="text-xl text-neutral-600 font-semibold">
                  {{ $jobVacancies->count() }} {{ Str::plural('vaga', $jobVacancies->count()) }}
                  {{ Str::plural('encontrada', $jobVacancies->count()) }}
               </p>
            </div>
         @endif
      </div>

      @if ($jobVacancies->isEmpty())
         <div class="text-center py-12">
            <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">Nenhuma vaga encontrada</h3>
            <p class="text-neutral-500 mb-4">Tente ajustar os filtros ou remova alguns termos de busca.</p>
            <x-mary-button label="Limpar filtros" wire:click="clearFilters" class="btn-outline" />
         </div>
      @else
         <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach ($jobVacancies as $job)
               <x-site.job-vacancy-card :job=$job />
            @endforeach
         </div>
      @endif
   </div>

</div>

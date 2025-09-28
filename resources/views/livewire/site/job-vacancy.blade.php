<div>
   <x-site.page-header
      title="Oportunidades de Emprego"
      subtitle="Consulte vagas de emprego para você impulsionar sua carreira."
      :breadcrumb="[['label' => 'Início', 'url' => route('site.home')], ['label' => 'Vagas']]" />

   <div class="bg-secondary-600 mx-auto px-4 sm:px-6 lg:px-8 pb-20">
      <!-- Filtros melhorados -->
      <div class="max-w-5xl mx-auto bg-white rounded-lg border border-neutral-200 p-4 mb-6">
         <div class="flex flex-col md:flex-row gap-4">
            <!-- Busca por palavra-chave -->
            <div class="flex-1">
               <x-mary-input type="text" placeholder="Buscar por palavra-chave..."
                  wire:model.defer="search" />
            </div>

            <!-- Cidade -->
            <div class="flex-1 md:flex-none md:w-1/4">
               <x-mary-input type="text" placeholder="Cidade"
                  wire:model.defer="city" />
            </div>

            <!-- Estado -->
            <div class="flex-1 md:flex-none md:w-1/4">
               <x-mary-select
                  placeholder="Todos os estados"
                  :options="$statesList"
                  wire:model.defer="state"
                  option-value="value"
                  option-label="label"
                  searchable />
            </div>

            <!-- Botões -->
            <div class="flex gap-2">
               <x-mary-button label="Filtrar" wire:click="applyFilters" icon="o-magnifying-glass" class="btn-primary" />
               <x-mary-button label="Limpar" wire:click="clearFilters" icon="o-x-mark" class="btn-outline" />
            </div>
         </div>
      </div>
   </div>

   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-20">
      @if ($jobVacancies->isEmpty())
         <div class="text-center py-12">
            <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor"
               viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">Nenhuma vaga encontrada</h3>
            <p class="text-neutral-500 mb-4">Tente ajustar os filtros ou remova alguns termos de busca.</p>
            <x-mary-button label="Limpar filtros" wire:click="clearFilters" class="btn-outline" />
         </div>
      @else
         <div class="mb-6">
            <p class="text-xl text-neutral-600 font-semibold">
               {{ $totalCount }} {{ Str::plural('vaga', $totalCount) }}  {{ Str::plural('encontrada', $totalCount) }}
            </p>
         </div>

         <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach ($jobVacancies as $job)
               <x-site.job-vacancy-card :job="$job" />
            @endforeach
         </div>

         <div class="mt-6">
            {{ $jobVacancies->links('vendor.pagination.simple-mary-ui') }}
         </div>
      @endif
   </div>
</div>

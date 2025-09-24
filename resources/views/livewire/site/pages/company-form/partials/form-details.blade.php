{{-- resources/views/livewire/site/pages/company-form/partials/info-card.blade.php --}}

@php
$isJob = $type === 'job';
$cardTitle = $isJob ? 'Informações da Vaga' : 'Informações do Formulário';
$titleLabel = $isJob ? 'Vaga' : 'Formulário';
@endphp

<h3 class="text-lg font-semibold text-neutral-900 mb-4">{{ $cardTitle }}</h3>

<div class="space-y-4">
   {{-- Título --}}
   <div class="flex items-start gap-2">
      <x-tabler-user class="w-auto h-5 text-neutral-600" />
      <div class="space-y-1">
         <p class="text-sm text-neutral-600">{{ $titleLabel }}</p>
         <p class="text-neutral-900 font-medium">{{ $info['title'] }}</p>
      </div>
   </div>

   {{-- Empresa --}}
   <div class="flex items-start gap-2">
      <x-tabler-building class="w-auto h-5 text-neutral-600" />
      <div class="space-y-1">
         <p class="text-sm text-neutral-600">Empresa</p>
         <p class="text-neutral-900 font-medium">{{ $info['company'] }}</p>
      </div>
   </div>

   {{-- Campos específicos para vaga --}}
   @if($isJob)
      @if ($info['department'])
         <div class="flex items-start gap-2">
            <x-tabler-building-warehouse class="w-auto h-5 text-neutral-600" />
            <div class="space-y-1">
               <p class="text-sm text-neutral-600">Departamento</p>
               <p class="text-neutral-900 font-medium">{{ $info['department'] }}</p>
            </div>
         </div>
      @endif

      @if ($info['level'])
         <div class="flex items-start gap-2">
            <x-tabler-list-numbers class="w-auto h-5 text-neutral-600" />
            <div class="space-y-1">
               <p class="text-sm text-neutral-600">Nível</p>
               <p class="text-neutral-900 font-medium">{{ $info['level'] }}</p>
            </div>
         </div>
      @endif

      @if ($info['employment_type'])
         <div class="flex items-start gap-2">
            <x-tabler-briefcase class="w-auto h-5 text-neutral-600" />
            <div class="space-y-1">
               <p class="text-sm text-neutral-600">Tipo de Contrato</p>
               <p class="text-neutral-900 font-medium">{{ $info['employment_type'] }}</p>
            </div>
         </div>
      @endif

      @if ($info['work_location'])
         <div class="flex items-start gap-2">
            <x-tabler-map-pin class="w-auto h-5 text-neutral-600" />
            <div class="space-y-1">
               <p class="text-sm text-neutral-600">Modalidade</p>
               <p class="text-neutral-900 font-medium">{{ $info['work_location'] }}</p>
            </div>
         </div>
      @endif

      @if ($info['city'] && $info['state'])
         <div class="flex items-start gap-2">
            <x-tabler-map class="w-auto h-5 text-neutral-600" />
            <div class="space-y-1">
               <p class="text-sm text-neutral-600">Localização</p>
               <p class="text-neutral-900 font-medium">{{ $info['city'] }}, {{ $info['state'] }}</p>
            </div>
         </div>
      @endif

      @if ($info['formatted_salary'])
         <div class="flex items-start gap-2">
            <x-tabler-currency-dollar class="w-auto h-5 text-neutral-600" />
            <div class="space-y-1">
               <p class="text-sm text-neutral-600">Salário</p>
               <p class="text-neutral-900 font-medium">{{ $info['formatted_salary'] }}</p>
            </div>
         </div>
      @endif

      @if ($info['application_deadline'])
         <div class="flex items-start gap-2">
            <x-tabler-calendar-time class="w-auto h-5 text-neutral-600" />
            <div class="space-y-1">
               <p class="text-sm text-neutral-600">Prazo para Candidatura</p>
               <p class="text-neutral-900 font-medium">
                  {{ \Carbon\Carbon::parse($info['application_deadline'])->format('d/m/Y') }}
               </p>
            </div>
         </div>
      @endif
   @endif
</div>
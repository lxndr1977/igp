{{-- resources/views/livewire/site/pages/company-form/partials/content-description.blade.php --}}

@php
   $isJob = ($type ?? 'form') === 'job';
   $entity = $isJob ? $jobVacancy ?? null : $form ?? null;
   $entityInfo = $isJob ? $jobInfo ?? [] : $formInfo ?? [];

   // Só cria breadcrumbs se a entidade existir
   $breadcrumbs = [];
   if ($entity && !empty($entityInfo['title'])) {
       $breadcrumbs[] = ['label' => 'Início', 'url' => route('site.home')];

       if ($isJob) {
           $breadcrumbs[] = ['label' => 'Vagas', 'url' => '#']; // Use '#' se a rota não existir
       } else {
           $breadcrumbs[] = ['label' => 'Formulários', 'url' => '#'];
       }

       $breadcrumbs[] = ['label' => $entityInfo['title']];
   }
@endphp

@if (!empty($breadcrumbs) && !empty($entityInfo['title']))
   <x-site.page-header
      title="{{ $entityInfo['title'] }}"
      :breadcrumb="$breadcrumbs"
      :slim=true
      class="py-0"
      container-class="px-0 mb-6"
      noPaddingTop="true" />
@endif

@if ($entity)
   <div class="space-y-6 mb-6 ">
      {{-- Conteúdo principal --}}
      {{-- Seções comuns --}}
      @if ($isJob)
         {{-- Seções específicas de vaga --}}
         @if (!empty($entity->description))
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
               <h3 class="text-xl font-semibold text-gray-900 mb-4">Descrição da Vaga</h3>
               <div class="text-gray-700  max-w-none leading-relaxed form-content-description">
                  {!! $entity->description !!}
               </div>
            </div>
         @endif

         @if (!empty($entity->requirements))
            <div class="bg-white rounded-lg border border-gray-200 p-6">
               <h3 class="text-xl font-semibold text-gray-900 mb-4">Requisitos</h3>
               <div class="text-gray-700 prose max-w-none leading-relaxed">
                  {!! nl2br(e($entity->requirements)) !!}
               </div>
            </div>
         @endif

         @if (!empty($entity->benefits))
            <div class="bg-white rounded-lg border border-gray-200 p-6">
               <h3 class="text-xl font-semibold text-gray-900 mb-4">Benefícios</h3>
               <div class="text-gray-700 prose max-w-none leading-relaxed">
                  {!! nl2br(e($entity->benefits)) !!}
               </div>
            </div>
         @endif
      @else
         {{-- Seção para formulário --}}
         @if (!empty($entity->description))
            <div class="bg-white rounded-lg border border-gray-200 p-6">
               <h3 class="text-xl font-semibold text-gray-900 mb-4">Descrição</h3>
               <div class="text-gray-700 prose max-w-none leading-relaxed">
                  {!! nl2br(e($entity->description)) !!}
               </div>
            </div>
         @endif

         {{-- Adicione outras seções específicas do form se necessário --}}
         @if (!empty($entity->instructions))
            <div class="bg-white rounded-lg border border-gray-200 p-6">
               <h3 class="text-xl font-semibold text-gray-900 mb-4">Instruções</h3>
               <div class="text-gray-700 prose max-w-none leading-relaxed">
                  {!! nl2br(e($entity->instructions)) !!}
               </div>
            </div>
         @endif
      @endif
   </div>
@endif


<style>
   .form-content-description ul {
      list-style-type: disc !important;
      margin-left: 1rem !important;
      padding-left: 1rem !important;
   }

   .form-content-description ul li {
      margin: 0 !important;
   }

   .form-content-description ul li {
      list-style: disc !important;
   }

   .form-content-description ol {
      list-style-type: decimal !important;
      margin-left: 1rem !important;
      padding-left: 1rem !important;
   }

   .form-content-description ol li {
      list-style: decimal !important;
      margin: 0 !important;
   }
</style>

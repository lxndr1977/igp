<div class="w-full">
   @if ($isJobVacancy && $jobVacancy)
      @php $jobInfo = $this->getJobVacancyInfo(); @endphp

      <div class="flex flex-col md:flex-row gap-8 mb-8">
         <div class="md:w-2/3">
            @include('livewire.site.pages.company-form.partials.content-description', [
                'type' => 'job',
                'jobVacancy' => $jobVacancy,
                'jobInfo' => $jobInfo,
            ])

            <x-mary-button
               wire:click="startForm"
               icon="tabler.click"
               class="btn-primary px-12 py-6 text-base mt-6">
               Quero me candidatar
            </x-mary-button>
         </div>

         <div class="md:w-1/3">
            <div class="bg-white shadow rounded-lg p-6 md:p-8 sticky top-4">
               @include('livewire.site.pages.company-form.partials.info-card', [
                   'type' => 'job',
                   'info' => $jobInfo,
               ])
            </div>
         </div>
      </div>
   @else
      @php $formInfo = $this->getFormInfo(); @endphp

      <div class="flex flex-col md:flex-row gap-8 mb-8">
         <div class="md:w-2/3">
            @include('livewire.site.pages.company-form.partials.content-description', [
                'type' => 'form',
                'form' => $this->form ?? null,
                'formInfo' => $formInfo,
            ])

            <x-mary-button
               wire:click="startForm"
               icon="tabler.click"
               class="btn-primary px-12 py-6 text-base mt-6"
               label="Preencher Formulário" />
         </div>

         <div class="md:w-1/3">
            <div class="space-y-4 ">
               <div class="bg-white rounded-lg p-6 md:p-8 border border-neutral-200 sticky top-12">

                  @include('livewire.site.pages.company-form.partials.info-card', [
                      'type' => 'form',
                      'info' => $formInfo,
                  ])
               </div>
            </div>
         </div>
      </div>
   @endif
</div>

<div class="bg-white w-full rounded-lg shadow p-8 border border-neutral-200"
   x-data="{
       currentStep: @entangle('currentStep')
   }"
   x-init="$watch('currentStep', value => {
       window.scrollTo({ top: 0, behavior: 'smooth' });
   })">


   <form wire:submit.prevent="submit" class="mx-auto">

      @if (session()->has('error'))
         <x-mary-alert
            icon="o-exclamation-triangle"
            title="Atenção!"
            description="{{ session('error') }}"
            class="alert alert-error alert-soft mb-6 text-base" />
      @endif

      @if ($form->sections->count() > 1)
         @php $totalSteps = $form->sections->count(); @endphp

         <div class="mb-8">
            <div class="bg-gray-200 rounded-full h-2.5">
               <div class="bg-primary h-2.5 rounded-full"
                  style="width: {{ ($currentStep / $totalSteps) * 100 }}%">
               </div>
            </div>
            <p class="text-center text-sm text-gray-600 mt-2">Passo {{ $currentStep }} de {{ $totalSteps }}</p>
         </div>

         <div class="space-y-6">
            {{-- Step 1: Campos do respondente --}}
            <div @if ($currentStep !== 1) style="display: none;" @endif>
               @include('livewire.site.pages.company-form.partials.respondent-fields')
            </div>

            {{-- Steps 2+: Seções do formulário --}}
            @foreach ($form->sections as $section)
               <div wire:key="section-{{ $section->id }}"
                  @if ($currentStep !== $loop->iteration) style="display: none;" @endif>
                  <div class="mb-6">
                     <h3 class="text-lg font-medium text-gray-900">{{ $section->title }}</h3>
                     @if ($section->description)
                        <p class="mt-1 text-sm text-gray-500">{{ $section->description }}</p>
                     @endif
                  </div>

                  <div class="space-y-6">
                     @php $p = 'livewire.site.pages.company-form.partials.'; @endphp
                     @foreach ($section->fields as $field)
                        @include($p . 'form-field-renderer', ['field' => $field])
                     @endforeach
                  </div>
               </div>
            @endforeach
         </div>

         {{-- Botões de navegação --}}
         <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
            @if ($currentStep > 1)
               <x-mary-button
                  wire:click="previousStep"
                  icon="tabler.arrow-left"
                  class="text-base py-6 order-2 md:order-1 w-full md:w-auto flex-1"
                  label="Voltar" />
            @else
               <x-mary-button
                  wire:click="backToIntro"
                  icon="tabler.arrow-left"
                  class="text-base py-6 order-2 md:order-1 w-full md:w-auto flex-1"
                  label="Voltar" />
            @endif

            @if ($currentStep < $totalSteps)
               <x-mary-button
                  wire:click="nextStep"
                  class="btn-primary text-base order-1 md:order-2 py-6 w-full md:w-auto flex-1"
                  icon-right="tabler.arrow-right"
                  label="Avançar"
                  spinner="nextStep" />
            @else
               <x-mary-button
                  wire:click="submit"
                  wire:loading.attr="disabled"
                  icon="tabler.send"
                  label="{{ $isJobVacancy && $jobVacancy ? 'Enviar currículo' : 'Enviar respostas' }}"
                  spinner="submit"
                  class="btn btn-primary text-base py-6 order-1 md:order-2 w-full md:w-auto flex-1" />
            @endif
         </div>

      @else
         {{-- Formulário sem seções (single page) --}}
         <div class="space-y-6">
            @include('livewire.site.pages.company-form.partials.respondent-fields')

            @php $p = 'livewire.site.pages.company-form.partials.'; @endphp
            
            @foreach ($this->getAllFields() as $field)
               @include($p . 'form-field-renderer', ['field' => $field])
            @endforeach
         </div>

         <div class="pt-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-y-6">
               <x-mary-button
                  wire:click="backToIntro"
                  icon="tabler.arrow-left"
                  class="text-base py-6 w-full md:w-auto order-2 md:order-1"
                  label="Voltar às Informações" />

               <x-mary-button
                  wire:click="submit"
                  wire:loading.attr="disabled"
                  icon="tabler.send"
                  label="{{ $isJobVacancy && $jobVacancy ? 'Enviar currículo' : 'Enviar respostas' }}"
                  spinner="submit"
                  class="btn btn-primary w-full md:w-auto text-base py-6 order-1 md:order-2" />
            </div>
         </div>
      @endif
   </form>
</div>
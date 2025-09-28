<div class="bg-neutral-100">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28">
      <div class="pb-20">
         @if ($submitted)
            @include('livewire.site.pages.company-form.partials.form-submit-message')
         @endif

         @if ($errors->has('rate_limit'))
            <x-mary-alert
               icon="o-exclamation-triangle"
               title="Atenção!"
               description="{{ $errors->first('rate_limit') }}"
               class="alert alert-error alert-soft mb-6 text-base" />
         @endif

         @if ($errors->has('respondent_email'))
            <x-mary-alert
               icon="o-exclamation-triangle"
               title="Atenção!"
               description="{{ $errors->first('respondent_email') }}"
               class="alert alert-error alert-soft mb-6 text-base" />
         @endif

         @if ($showIntroScreen && !$submitted)
            @include('livewire.site.pages.company-form.partials.form-intro')
         @endif

         @if (!$showIntroScreen && !$submitted)
            @php
               $isJob = $isJobVacancy && $jobVacancy;
               $info = $isJob ? $this->getJobVacancyInfo() : $this->getFormInfo();
            @endphp

            <div class="max-w-7xl mx-auto flex flex-col justify-start md:flex-row gap-8">
               <div class="w-full md:w-2/3">
                  {{-- Header com breadcrumbs apenas para job --}}
                  @if ($isJob)
                     <x-site.page-header
                        title="{{ $info['title'] }}"
                        :breadcrumb="[
                            ['label' => 'Início', 'url' => route('site.home')],
                            ['label' => 'Vagas', 'url' => route('site.job-vacancies')],
                            ['label' => $info['title']],
                        ]"
                        :slim=true
                        class="py-0"
                        container-class="px-0 mb-6"
                        noPaddingTop="true" />
                  @else
                     <x-site.page-header
                        title="{{ $info['title'] }}"
                        :breadcrumb="[
                            ['label' => 'Início', 'url' => route('site.home')],
                            ['label' => 'Formulários'],
                            ['label' => $info['title']],
                        ]"
                        :slim=true
                        class="py-0"
                        container-class="px-0 mb-6"
                        noPaddingTop="true" />
                  @endif

                  @include('livewire.site.pages.company-form.partials.form')
               </div>

               <div class="w-full md:w-1/3">
                  <div class="bg-white rounded-lg shadow p-6 border border-neutral-200 sticky top-12">
                     @include('livewire.site.pages.company-form.partials.info-card', [
                         'type' => $isJob ? 'job' : 'form',
                         'info' => $info,
                     ])
                  </div>
               </div>
            </div>
         @endif
      </div>
   </div>
</div>

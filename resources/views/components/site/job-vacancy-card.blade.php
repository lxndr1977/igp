<div
   class="bg-white rounded-xl p-6 h-full flex flex-col border border-neutral-200  hover:shadow-lg hover:-translate-y-1 transition duration-300">
   <a
      href="{{ route('company.form.vacancy', [
          'companyId' => $job->company->id,
          'formSlug' => $job->slug,
      ]) }}">

      <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center mb-6">
         <x-tabler-user class="w-6 h-6 text-secondary-600" />
      </div>

      <h2 class="text-lg lg:text-xl text-neutral-900 font-semibold mb-2">{{ $job->title }}</h2>

      <div class="pb-6 space-y-2">
         <div class="flex no-wrap items-center gap-x-1 mb-2">
            <x-tabler-building class="w-auto h-4 text-neutral-600" />

            <p class="text-neutral-600 ">{{ $job->company_name }}</p>
         </div>

         <div class="flex no-wrap items-center gap-2">
            <x-tabler-map class="w-auto h-4 text-neutral-600" />

            <p class="text-neutral-600 ">
               @if ($job->city || $job->state)
                  {{ $job->city ? $job->city . ', ' : '' }}{{ $job->state ?? '' }}
               @else
                  Não informado
               @endif
            </p>
         </div>

         <div class="flex no-wrap items-center gap-2">
            <x-tabler-map-pin class="w-auto h-4 text-neutral-600" />
            <p class="text-neutral-600 font-medium pt-0.5">{{ $job->work_location_label }}</p>
         </div>

         <div class="flex no-wrap items-center gap-2">
            <x-tabler-briefcase class="w-auto h-4 text-neutral-600" />
            <p class="text-neutral-600 font-medium pt-0.5">{{ $job->employment_type_label }}</p>
         </div>
      </div>

      <div class="flex no-wrap items-center gap-1 mb-6">
         <x-tabler-currency-dollar class="w-auto h-4 text-neutral-900" />
         <p class="text-neutral-900 font-medium pt-0.5">{{ $job->formatted_salary }}</p>
      </div>

      <x-mary-button
         link="{{ route('company.form.vacancy', [
             'companyId' => $job->company->id,
             'formSlug' => $job->slug,
         ]) }}"
         icon="tabler.click"
         label="Quero me candidatar"
         class="btn btn-primary text-base px-12 py-6" />
   </a>
</div>

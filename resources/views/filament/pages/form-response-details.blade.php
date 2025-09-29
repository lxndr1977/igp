<x-filament-panels::page>
   {{-- Header com informações principais --}}
   <x-filament::section>
      <x-slot name="heading">
         Informações do formulário
      </x-slot>
      <div>
         <dl class="grid gap-4 sm:grid-cols-3">
            <div>
               <dt class="text-xs font-medium text-gray-500">Empresa</dt>
               <dd class="mt-1 text-sm text-gray-950">
                  {{ $response->company_name }}
               </dd>
            </div>

            <div>
               <dt class="text-xs font-medium text-gray-500">Tipo do Formulário</dt>
               <dd class="mt-1 text-sm text-gray-950">
                  {{ $response->job_vacancy && $response->job_vacancy->exists() ? 'Vaga de emprego' : 'Formulário de Pesquisa' }}
               </dd>
            </div>

            <div>
               <dt class="text-xs font-medium text-gray-500">Título</dt>
               <dd class="mt-1 text-sm text-gray-950">
                  {{ $response->job_vacancy && $response->job_vacancy->exists() ? $response->jobVacancy->title : $response->companyFormTemplate->title }}
               </dd>
         </dl>
      </div>
   </x-filament::section>

   {{-- Informações do Respondente --}}
   <x-filament::section>
      <x-slot name="heading">
         Enviado por
      </x-slot>

      <dl class="grid gap-4 sm:grid-cols-3">
         <div>
            <dt class="text-xs font-medium text-gray-500">Nome</dt>
            <dd class="mt-1 text-sm text-gray-950">
               {{ $response->respondent_name ?: '—' }}

            </dd>
         </div>

         <div>
            <dt class="text-xs font-medium text-gray-500">Email</dt>
            <dd class="mt-1 text-sm">
               @if ($response->respondent_email)
                  <a href="mailto:{{ $response->respondent_email }}"
                     class="text-gray-950">
                     {{ $response->respondent_email }}
                  </a>
               @else
                  <span class="text-gray-950">—</span>
               @endif
            </dd>
         </div>
         
         <div>
            <dt class="text-xs font-medium text-gray-500">Data do Envio</dt>
            <dd class="mt-1 text-sm text-gray-950">
               {{ $response->submitted_at->format('d/m/Y H:i') }}

            </dd>
         </div>
      </dl>
   </x-filament::section>

   {{-- Respostas do Formulário --}}
   @forelse($this->getGroupedResponses() as $sectionTitle => $fieldResponses)
      <x-filament::section>
         <x-slot name="heading">
            {{ $sectionTitle }}
         </x-slot>

         <div class="space-y-6">
            @foreach ($fieldResponses->sortBy(fn($r) => $r->formTemplateField->order) as $fieldResponse)
               @php
                  $field = $fieldResponse->formTemplateField;
                  $formattedValue = $this->formatFieldValue($fieldResponse);
                  $value = $fieldResponse->value;
               @endphp

               <div>
                  {{-- Label do campo --}}
                  <div class="flex items-baseline justify-between gap-3 mb-2">
                     <label class="text-sm font-medium text-gray-950">
                        {{ $field->label }}
                        @if ($field->is_required)
                           <span class="text-danger-600">*</span>
                        @endif
                     </label>
                     <x-filament::badge color="gray" size="xs">
                        {{ $field->field_type_label }}
                     </x-filament::badge>
                  </div>

                  @if ($field->help_text)
                     <p class="mb-2 text-xs text-gray-500">
                        {{ $field->help_text }}
                     </p>
                  @endif

                  {{-- Valor da resposta --}}
                  <div class="rounded-lg bg-gray-50 px-3 py-2.5 dark:bg-white/5">
                     @if ($value !== null && $value !== '' && !(is_array($value) && empty($value)))
                        @if ($field->field_type === 'textarea')
                           <p class="whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300">{{ $value }}
                           </p>
                        @elseif($this->shouldShowAsBadge($field->field_type) && is_array($value))
                           <div class="flex flex-wrap gap-1.5">
                              @foreach (array_keys($value) as $valueItem)
                                 <x-filament::badge color="primary">
                                    {{ $valueItem }}
                                 </x-filament::badge>
                              @endforeach
                           </div>
                        @elseif($field->field_type === 'checkbox')
                           <div class="flex items-center gap-2">
                              <span class="text-sm text-gray-700 dark:text-gray-300">
                                 {{ isset($value[0]) && $value[0] === 'on' ? '✓ Sim' : '✗ Não' }}
                              </span>
                           </div>
                        @elseif($field->field_type === 'rating')
                           <div class="flex items-center gap-2">
                              <span class="text-lg text-yellow-500">
                                 @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= $value ? '★' : '☆' }}
                                 @endfor
                              </span>
                              <span class="text-xs text-gray-500">
                                 {{ $value }}/5
                              </span>
                           </div>
                        @elseif($field->field_type === 'scale')
                           <div class="flex items-center gap-3">
                              <div class="flex-1 h-1.5 bg-gray-200 rounded-full dark:bg-gray-700">
                                 <div class="h-1.5 rounded-full bg-primary-500"
                                    style="width: {{ ($value / 10) * 100 }}%">
                                 </div>
                              </div>
                              <span class="text-sm font-medium text-gray-700 dark:text-gray-300 min-w-[2.5rem]">
                                 {{ $value }}/10
                              </span>
                           </div>
                        @elseif($field->field_type === 'email')
                           <a href="mailto:{{ $value }}"
                              class="text-sm text-primary-600 hover:underline dark:text-primary-400">
                              {{ $value }}
                           </a>
                        @elseif($field->field_type === 'tel')
                           <a href="tel:{{ $value }}"
                              class="text-sm font-mono text-primary-600 hover:underline dark:text-primary-400">
                              {{ $value }}
                           </a>
                        @else
                           <p class="text-sm text-gray-700 dark:text-gray-300">
                              {{ $formattedValue }}
                           </p>
                        @endif
                     @else
                        <p class="text-sm italic text-gray-400 dark:text-gray-500">
                           Não respondido
                        </p>
                     @endif
                  </div>
               </div>
            @endforeach
         </div>
      </x-filament::section>
   @empty
      <x-filament::section>
         <div class="py-12 text-center">
            <div
               class="mx-auto w-12 h-12 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-3">
               <x-filament::icon
                  icon="heroicon-o-document-text"
                  class="w-6 h-6 text-gray-400" />
            </div>
            <h3 class="text-sm font-medium text-gray-950">
               Nenhuma resposta encontrada
            </h3>
            <p class="mt-1 text-xs text-gray-500">
               Este formulário ainda não possui respostas.
            </p>
         </div>
      </x-filament::section>
   @endforelse

   {{-- Ações --}}
   <div class="flex items-center justify-between">

      <x-filament::button
         color="gray"
         outlined
         tag="a"
         :href="url()->previous()"
         icon="heroicon-m-arrow-left">
         Voltar
      </x-filament::button>

   </div>
</x-filament-panels::page>

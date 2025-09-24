<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Informações do Respondente --}}
        <x-filament::section>
            <x-slot name="heading">
                Informações do Respondente
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Nome</x-slot>
                        <div class="text-sm text-gray-900">
                            {{ $response->respondent_name ?: 'Não informado' }}
                        </div>
                    </x-filament::fieldset>
                </div>

                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Email</x-slot>
                        <div class="text-sm text-gray-900">
                            @if($response->respondent_email)
                                <a href="mailto:{{ $response->respondent_email }}" 
                                   class="text-primary-600 hover:text-primary-500">
                                    {{ $response->respondent_email }}
                                </a>
                            @else
                                Não informado
                            @endif
                        </div>
                    </x-filament::fieldset>
                </div>

                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Endereço IP</x-slot>
                        <div class="text-sm text-gray-900 font-mono">
                            {{ $response->ip_address ?: 'Não informado' }}
                        </div>
                    </x-filament::fieldset>
                </div>

                <div>
                    <x-filament::fieldset>
                        <x-slot name="label">Submetido em</x-slot>
                        <div class="text-sm text-gray-900">
                            {{ $response->submitted_at ? $response->submitted_at->format('d/m/Y H:i:s') : 'Não informado' }}
                        </div>
                    </x-filament::fieldset>
                </div>
            </div>

            @if($response->user_agent)
                <div class="mt-4">
                    <x-filament::fieldset>
                        <x-slot name="label">Agente do usuário</x-slot>
                        <div class="text-sm text-gray-900 break-all">
                            {{ $response->user_agent }}
                        </div>
                    </x-filament::fieldset>
                </div>
            @endif
        </x-filament::section>

        {{-- Respostas do Formulário --}}
        <x-filament::section>
            <x-slot name="heading">
                Respostas do Formulário
            </x-slot>

            @if($response->company_name)
                <x-slot name="description">
                    Empresa: {{ $response->company_name }}
                </x-slot>
            @endif

            @forelse($this->getGroupedResponses() as $sectionTitle => $fieldResponses)
                <x-filament::section class="mt-6">
                    <x-slot name="heading">
                        {{ $sectionTitle }}
                    </x-slot>

                    <div class="space-y-4">
                        @foreach($fieldResponses->sortBy(fn($r) => $r->formTemplateField->order) as $fieldResponse)
                            @php
                                $field = $fieldResponse->formTemplateField;
                                $formattedValue = $this->formatFieldValue($fieldResponse);
                            @endphp

                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">
                                            {{ $field->label }}
                                            @if($field->is_required)
                                                <span class="text-danger-600 ml-1">*</span>
                                            @endif
                                        </h4>
                                        @if($field->help_text)
                                            <p class="text-xs text-gray-600 mt-1">{{ $field->help_text }}</p>
                                        @endif
                                    </div>
                                    <x-filament::badge
                                        :color="$this->getFieldTypeColor($field->field_type)"
                                        size="xs"
                                    >
                                        {{ $field->field_type }}
                                    </x-filament::badge>
                                </div>

                                <div class="mt-2">
                                    @if($fieldResponse->value !== null && $fieldResponse->value !== '')
                                        @if($field->field_type === 'textarea')
                                            <div class="bg-white border border-gray-200 rounded p-3 text-sm text-gray-900 whitespace-pre-wrap">{{ $fieldResponse->value }}</div>
                                        @elseif($this->shouldShowAsBadge($field->field_type) && is_array($fieldResponse->value))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($fieldResponse->value as $value)
                                                    <x-filament::badge color="primary" size="sm">
                                                        {{ $value }}
                                                    </x-filament::badge>
                                                @endforeach
                                            </div>
                                        @elseif($field->field_type === 'rating')
                                            <div class="flex items-center space-x-2">
                                                <div class="text-yellow-500 text-lg">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        {{ $i <= $fieldResponse->value ? '★' : '☆' }}
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-600">({{ $fieldResponse->value }}/5)</span>
                                            </div>
                                        @elseif($field->field_type === 'scale')
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" 
                                                         style="width: {{ ($fieldResponse->value / 10) * 100 }}%"></div>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 min-w-[2rem]">{{ $fieldResponse->value }}/10</span>
                                            </div>
                                        @elseif($field->field_type === 'email')
                                            <a href="mailto:{{ $fieldResponse->value }}" 
                                               class="text-primary-600 hover:text-primary-500 underline">
                                                {{ $fieldResponse->value }}
                                            </a>
                                        @elseif($field->field_type === 'tel')
                                            <a href="tel:{{ $fieldResponse->value }}" 
                                               class="text-primary-600 hover:text-primary-500 underline font-mono">
                                                {{ $fieldResponse->value }}
                                            </a>
                                        @else
                                            <div class="text-sm text-gray-900">{{ $formattedValue }}</div>
                                        @endif
                                    @else
                                        <div class="text-sm text-gray-500 italic">Não respondido</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @empty
                <div class="text-center py-12">
                    <x-filament::icon 
                        icon="heroicon-o-document-text" 
                        class="mx-auto h-12 w-12 text-gray-400"
                    />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        Nenhuma resposta encontrada
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Este formulário ainda não possui respostas.
                    </p>
                </div>
            @endforelse
        </x-filament::section>

        {{-- Ações --}}
        <div class="flex justify-end space-x-3">
            <x-filament::button
                color="gray"
                tag="a"
                :href="url()->previous()"
                icon="heroicon-m-arrow-left"
            >
                Voltar
            </x-filament::button>

            @if($response->formFieldResponses->count() > 0)
                <x-filament::button
                    color="primary"
                    icon="heroicon-m-document-arrow-down"
                    onclick="window.print()"
                >
                    Imprimir
                </x-filament::button>
            @endif
        </div>
    </div>
</x-filament-panels::page>
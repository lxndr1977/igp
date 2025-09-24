<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Informações do Respondente --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Informações do Respondente
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $formResponse->respondent_name ?? 'Não informado' }}
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $formResponse->respondent_email ?? 'Não informado' }}
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data/Hora de Envio</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $formResponse->submitted_at?->format('d/m/Y H:i:s') ?? 'Não informado' }}
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">IP</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                        {{ $formResponse->ip_address ?? 'Não informado' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Respostas do Formulário --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                Respostas do Formulário: {{ $formTemplate->formTemplate->name }}
            </h3>

            @if(empty($formStructure))
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma resposta encontrada.</p>
                </div>
            @else
                <div class="space-y-8">
                    @foreach($formStructure as $section)
                        <div class="border-l-4 border-primary-500 pl-4">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
                                {{ $section['title'] }}
                            </h4>
                            
                            @if($section['description'])
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    {{ $section['description'] }}
                                </p>
                            @endif

                            <div class="space-y-4">
                                @foreach($section['fields'] as $field)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex items-start justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $field['label'] }}
                                                @if($field['required'])
                                                    <span class="text-red-500">*</span>
                                                @endif
                                            </label>
                                            
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                                {{ ucfirst($field['type']) }}
                                            </span>
                                        </div>

                                        <div class="mt-2">
                                            @if($field['response'])
                                                @php
                                                    $response = $field['response'];
                                                    $value = $response['formatted_value'];
                                                @endphp

                                                @if($field['type'] === 'file')
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                                        </svg>
                                                        <a href="#" class="text-primary-600 hover:text-primary-500 underline">
                                                            {{ $value }}
                                                        </a>
                                                    </div>
                                                @elseif(in_array($field['type'], ['checkbox', 'select_multiple']))
                                                    <div class="flex flex-wrap gap-2">
                                                        @if(is_array($response['value']))
                                                            @foreach($response['value'] as $item)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                                                    {{ $item }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                                                {{ $value }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @elseif($field['type'] === 'textarea')
                                                    <div class="bg-white dark:bg-gray-800 border rounded-lg p-3">
                                                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $value }}</p>
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-900 dark:text-white">
                                                        {{ $value ?: 'Não respondido' }}
                                                    </p>
                                                @endif
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                                                    Não respondido
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Informações Técnicas --}}
        @if($formResponse->user_agent)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Informações Técnicas
                </h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Agent</label>
                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400 font-mono break-all">
                        {{ $formResponse->user_agent }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
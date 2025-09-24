<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {{ $companyName ? "Respostas - {$companyName}" : 'Todas as Respostas' }}
            </h1>
            
            {{-- Informações básicas --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                @if($formTemplateName)
                    <div class="flex items-center gap-2">
                        <x-heroicon-m-document class="w-4 h-4 text-blue-500" />
                        <span class="font-medium">{{ $formTemplateName }}</span>
                    </div>
                @endif
                
                @if($createdAt)
                    <div class="flex items-center gap-2">
                        <x-heroicon-m-calendar class="w-4 h-4 text-gray-500" />
                        <span>{{ $createdAt->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Status badges --}}
    @if($hasGeneralResponses || $hasJobResponses || (!$hasGeneralResponses && !$hasJobResponses && $formId))
        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2 mb-2">
                <x-heroicon-m-chart-bar class="w-4 h-4 text-gray-400" />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipos de Resposta:</span>
            </div>
            
            <div class="flex flex-wrap gap-2">
                @if($hasGeneralResponses)
                    <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-medium">
                        <x-heroicon-m-document-text class="w-4 h-4" />
                        <span>Formulário Geral</span>
                    </div>
                @endif
                
                @if($hasJobResponses)
                    <div class="flex items-center gap-2 px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg text-sm font-medium">
                        <x-heroicon-m-briefcase class="w-4 h-4" />
                        <span>Formulário de Vaga</span>
                    </div>
                @endif
                
                @if(!$hasGeneralResponses && !$hasJobResponses && $formId)
                    <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg text-sm">
                        <x-heroicon-m-exclamation-triangle class="w-4 h-4" />
                        <span>Nenhuma resposta encontrada</span>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
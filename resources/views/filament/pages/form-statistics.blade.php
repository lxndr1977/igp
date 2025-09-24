<x-filament-panels::page>
   <div class="space-y-6">
      <!-- Formulário de seleção -->
      <div class="bg-white rounded-lg shadow p-6">
         {{ $this->form }}
      </div>

      @if ($selectedForm)
         <!-- Estatísticas gerais -->
         <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
               <div class="flex items-center">
                  <div class="flex-shrink-0">
                     <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-document-text class="w-5 h-5 text-white" />
                     </div>
                  </div>
                  <div class="ml-4">
                     <p class="text-sm font-medium text-gray-500">Total de Respostas</p>
                     <p class="text-2xl font-semibold text-gray-900">{{ $this->totalResponses }}</p>
                  </div>
               </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
               <div class="flex items-center">
                  <div class="flex-shrink-0">
                     <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-calendar class="w-5 h-5 text-white" />
                     </div>
                  </div>
                  <div class="ml-4">
                     <p class="text-sm font-medium text-gray-500">Este Mês</p>
                     <p class="text-2xl font-semibold text-gray-900">{{ $this->responsesThisMonth }}</p>
                  </div>
               </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
               <div class="flex items-center">
                  <div class="flex-shrink-0">
                     <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <x-heroicon-o-clock class="w-5 h-5 text-white" />
                     </div>
                  </div>
                  <div class="ml-4">
                     <p class="text-sm font-medium text-gray-500">Esta Semana</p>
                     <p class="text-2xl font-semibold text-gray-900">{{ $this->responsesThisWeek }}</p>
                  </div>
               </div>
            </div>
         </div>

         <!-- Estatísticas por campo -->
         @if ($fieldStatistics->isNotEmpty())
            <div class="space-y-6">
               <h2 class="text-xl font-semibold text-gray-900">Estatísticas por Campo</h2>

               @foreach ($fieldStatistics as $stat)
                  <div class="bg-white rounded-lg shadow p-6">
                     <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $stat['field']->label }}</h3>
                        <p class="text-sm text-gray-500">
                           Tipo: {{ ucfirst($stat['field']->type) }} |
                           Total de respostas: {{ $stat['total_responses'] }}
                        </p>
                     </div>

                     @if ($stat['can_chart'])
                        @livewire(\App\Filament\Widgets\FormFieldChart::class, ['fieldId' => $stat['field']->id])

                        <!-- Gráfico para campos que suportam -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                           <!-- Gráfico de barras -->
                           <div>
                              <h4 class="text-md font-medium text-gray-700 mb-3">Distribuição das Respostas</h4>
                              <div class="space-y-2">
                                 @foreach ($stat['chart_data'] as $data)
                                    <div class="flex items-center">
                                       <div class="w-24 text-sm text-gray-600 truncate">
                                          {{ $data['label'] }}
                                       </div>
                                       <div class="flex-1 mx-3">
                                          <div class="bg-gray-200 rounded-full h-4">
                                             <div class="bg-blue-500 h-4 rounded-full"
                                                style="width: {{ $data['percentage'] }}%"></div>
                                          </div>
                                       </div>
                                       <div class="w-16 text-sm text-gray-600 text-right">
                                          {{ $data['value'] }} ({{ $data['percentage'] }}%)
                                       </div>
                                    </div>
                                 @endforeach
                              </div>
                           </div>

                           <!-- Tabela de dados -->
                           <div>
                              <h4 class="text-md font-medium text-gray-700 mb-3">Dados Detalhados</h4>
                              <div class="overflow-hidden border border-gray-200 rounded-lg">
                                 <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                       <tr>
                                          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                             Opção
                                          </th>
                                          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                             Quantidade
                                          </th>
                                          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                             Percentual
                                          </th>
                                       </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                       @foreach ($stat['chart_data'] as $data)
                                          <tr>
                                             <td class="px-4 py-2 text-sm text-gray-900">
                                                {{ $data['label'] }}
                                             </td>
                                             <td class="px-4 py-2 text-sm text-gray-900">
                                                {{ $data['value'] }}
                                             </td>
                                             <td class="px-4 py-2 text-sm text-gray-900">
                                                {{ $data['percentage'] }}%
                                             </td>
                                          </tr>
                                       @endforeach
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     @else
                        <!-- Lista de respostas para campos de texto -->
                        <div>
                           <h4 class="text-md font-medium text-gray-700 mb-3">Respostas</h4>
                           <div class="max-h-96 overflow-y-auto">
                              <div class="space-y-3">
                                 @forelse($stat['text_responses'] as $response)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                       <p class="text-sm text-gray-900">{{ $response['value'] ?: 'Sem resposta' }}</p>
                                       <p class="text-xs text-gray-500 mt-1">{{ $response['submitted_at'] }}</p>
                                    </div>
                                 @empty
                                    <p class="text-sm text-gray-500">Nenhuma resposta encontrada.</p>
                                 @endforelse
                              </div>
                           </div>
                        </div>
                     @endif
                  </div>
               @endforeach
            </div>
         @endif
      @else
         <div class="bg-white rounded-lg shadow p-6 text-center">
            <x-heroicon-o-chart-bar class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">Selecione um formulário</h3>
            <p class="mt-1 text-sm text-gray-500">
               Escolha um formulário acima para visualizar suas estatísticas.
            </p>
         </div>
      @endif
   </div>
</x-filament-panels::page>

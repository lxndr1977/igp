<?php

namespace App\Filament\Widgets;

use App\Models\FormTemplateField;
use App\Models\FormFieldResponse;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class FormFieldChart extends ChartWidget
{
   protected ?string $heading = 'Distribuição de Respostas por Campo';
   protected static ?int $sort = 2;

   public ?int $fieldId = null;
   public ?FormTemplateField $field = null;


   // Em App\Filament\Pages\Dashboard.php ou config/filament.php
   public static function canView(): bool
   {
      return false;
   }


   protected function getData(): array
   {
      if (!$this->fieldId) {
         return [
            'datasets' => [],
            'labels' => [],
         ];
      }

      $this->field = FormTemplateField::find($this->fieldId);

      if (!$this->field || !$this->canGenerateChart($this->field->field_type)) {
         return [
            'datasets' => [],
            'labels' => [],
         ];
      }

      // Buscar todas as respostas do formulário que contém este campo
      $allFormResponses = FormFieldResponse::where('form_template_field_id', $this->fieldId)
         ->with('formResponse')
         ->get();

      // Total único de form_response_id para contar respostas do formulário
      $totalFormResponses = $allFormResponses->pluck('form_response_id')->unique()->count();

      // Respostas específicas deste campo (não nulas)
      $responses = $allFormResponses->whereNotNull('value');

      if ($totalFormResponses === 0) {
         return [
            'datasets' => [],
            'labels' => [],
         ];
      }

      $chartData = $this->processResponsesForChart($responses, $totalFormResponses);

      return [
         'datasets' => [
            [
               'label' => $this->field->label,
               'data' => $chartData['values'],
               'backgroundColor' => $this->generateColors(count($chartData['labels'])),
               'borderColor' => $this->generateBorderColors(count($chartData['labels'])),
               'borderWidth' => 1,
            ],
         ],
         'labels' => $chartData['labels'],
      ];
   }

   protected function getType(): string
{
    if (!$this->field) {
        return 'bar';
    }

    // Determinar o tipo de gráfico baseado no tipo do campo
    return match ($this->field->field_type) {
        // Para escolhas binárias ou de um único item de uma lista.
        // Mostra a proporção de um todo (100%).
        'checkbox', 'boolean', 'radio', 'select', 'select_single' => 'pie',

        // Para escolhas múltiplas ou escalas.
        // Compara a contagem/popularidade entre itens independentes.
        'select_multiple', 'scale', 'rating' => 'bar',

        // 'bar' como padrão para qualquer outro tipo.
        default => 'bar'
    };
}

   protected function getOptions(): array
   {
      $options = [
         'plugins' => [
            'legend' => [
               'display' => true,
               'position' => 'bottom',
            ],
            'tooltip' => [
               'callbacks' => [
                  'label' => 'function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ": " + context.parsed + " (" + percentage + "%)";
                        }'
               ]
            ]
         ],
         'responsive' => true,
         'maintainAspectRatio' => false,
      ];

      // Configurações específicas para gráficos de barras
      if (in_array($this->getType(), ['bar'])) {
         $options['scales'] = [
            'y' => [
               'beginAtZero' => true,
               'ticks' => [
                  'stepSize' => 1,
               ],
            ],
         ];
      }

      return $options;
   }

   protected function canGenerateChart(string $fieldType): bool
   {
      return in_array($fieldType, [
         'select',
         'select_single',
         'select_multiple',
         'scale',
         'radio',
         'checkbox',
         'boolean',
         'rating'
      ]);
   }

   protected function processResponsesForChart(Collection $responses, int $totalFormResponses): array
   {
      $isCheckboxField = $this->field && $this->field->field_type === 'checkbox';
      $valueCounts = [];

      if ($isCheckboxField) {
         // --- LÓGICA CORRIGIDA E SIMPLIFICADA PARA CHECKBOX ---
         // Considera que $response->value já é um array como ['on'] ou ['off'] devido ao $casts no Model.

         // 1. Mapeia as respostas para extrair o valor 'on' ou 'off' de dentro do array.
         $unwrappedValues = $responses->map(function ($response) {
            return is_array($response->value) ? ($response->value[0] ?? null) : null;
         });

         // 2. Conta as ocorrências de 'on' e 'off'.
         $counts = $unwrappedValues->countBy();

         $marcados = $counts->get('on', 0);
         $desmarcados = $counts->get('off', 0);

         // 3. Lógica de segurança para garantir que a contagem total corresponda ao total de envios.
         // Se houver envios antigos sem um registro 'off', eles serão contados como "Não".
         $totalRegistrado = $marcados + $desmarcados;
         if ($totalRegistrado < $totalFormResponses) {
            $desmarcados += ($totalFormResponses - $totalRegistrado);
         }

         // 4. Prepara a contagem final para o gráfico com as labels corretas.
         $valueCounts = ['Sim' => $marcados, 'Não' => $desmarcados];
      } else {
         // --- LÓGICA ORIGINAL PARA OS OUTROS CAMPOS (INTACTA) ---
         // Esta é a sua versão que você confirmou que funciona para os outros tipos de gráfico.

         foreach ($responses as $response) {
            $value = $response->value;

            // O cast 'json' no model já pode ter decodificado o valor.
            // Esta verificação extra de is_string garante compatibilidade com dados antigos.
            if (is_string($value) && $this->isJson($value)) {
               $value = json_decode($value, true);
            }

            if (is_array($value)) {
               if ($this->isAssocArray($value)) {
                  foreach (array_keys($value) as $item) {
                     $key = $item ?: 'Sem resposta';
                     $valueCounts[$key] = ($valueCounts[$key] ?? 0) + 1;
                  }
               } else {
                  foreach ($value as $item) {
                     $key = $item ?: 'Sem resposta';
                     $valueCounts[$key] = ($valueCounts[$key] ?? 0) + 1;
                  }
               }
            } else {
               $key = $value ?: 'Sem resposta';
               $valueCounts[$key] = ($valueCounts[$key] ?? 0) + 1;
            }
         }
      }

      // --- PREPARAÇÃO FINAL DA SAÍDA (INTACTA) ---
      // Ordena os resultados e formata para a estrutura que o getData() espera.
      $sorted = collect($valueCounts)->sortDesc();

      return [
         'labels' => $sorted->keys()->toArray(),
         'values' => $sorted->values()->toArray(),
      ];
   }

   // Método helper para verificar se string é JSON
   private function isJson($string): bool
   {
      if (!is_string($string)) {
         return false;
      }

      json_decode($string);
      return json_last_error() === JSON_ERROR_NONE;
   }

   // Método helper para verificar se array é associativo
   private function isAssocArray(array $arr): bool
   {
      if (empty($arr)) {
         return false;
      }
      return array_keys($arr) !== range(0, count($arr) - 1);
   }

   protected function generateColors(int $count): array
   {
      $colors = [
         'rgba(59, 130, 246, 0.8)',   // blue
         'rgba(16, 185, 129, 0.8)',   // green
         'rgba(245, 158, 11, 0.8)',   // yellow
         'rgba(239, 68, 68, 0.8)',    // red
         'rgba(139, 92, 246, 0.8)',   // purple
         'rgba(236, 72, 153, 0.8)',   // pink
         'rgba(14, 165, 233, 0.8)',   // sky
         'rgba(34, 197, 94, 0.8)',    // emerald
         'rgba(251, 146, 60, 0.8)',   // orange
         'rgba(168, 85, 247, 0.8)',   // violet
      ];

      $result = [];
      for ($i = 0; $i < $count; $i++) {
         $result[] = $colors[$i % count($colors)];
      }

      return $result;
   }

   protected function generateBorderColors(int $count): array
   {
      $colors = [
         'rgba(59, 130, 246, 1)',     // blue
         'rgba(16, 185, 129, 1)',     // green
         'rgba(245, 158, 11, 1)',     // yellow
         'rgba(239, 68, 68, 1)',      // red
         'rgba(139, 92, 246, 1)',     // purple
         'rgba(236, 72, 153, 1)',     // pink
         'rgba(14, 165, 233, 1)',     // sky
         'rgba(34, 197, 94, 1)',      // emerald
         'rgba(251, 146, 60, 1)',     // orange
         'rgba(168, 85, 247, 1)',     // violet
      ];

      $result = [];
      for ($i = 0; $i < $count; $i++) {
         $result[] = $colors[$i % count($colors)];
      }

      return $result;
   }

   public function setField(int $fieldId): static
   {
      $this->fieldId = $fieldId;
      return $this;
   }
}

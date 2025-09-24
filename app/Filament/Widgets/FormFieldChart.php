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
    return  false; }


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

        $responses = FormFieldResponse::where('form_template_field_id', $this->fieldId)
            ->whereNotNull('value')
            ->get();

        if ($responses->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $chartData = $this->processResponsesForChart($responses);
        
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
        return match($this->field->type) {
            'radio', 'select', 'select_single' => 'pie',
            'checkbox' => 'bar',
            'boolean' => 'doughnut',
            'rating' => 'bar',
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
            'radio',
            'checkbox',
            'boolean',
            'rating'
        ]);
    }

    protected function processResponsesForChart(Collection $responses): array
    {
        $valueCounts = collect();

        foreach ($responses as $response) {
            $value = $response->value;
            
            if (is_array($value)) {
                // Para checkboxes que podem ter múltiplos valores
                foreach ($value as $item) {
                    $key = $item ?: 'Sem resposta';
                    $valueCounts[$key] = ($valueCounts[$key] ?? 0) + 1;
                }
            } else {
                $key = $value ?: 'Sem resposta';
                $valueCounts[$key] = ($valueCounts[$key] ?? 0) + 1;
            }
        }

        // Ordenar por quantidade (decrescente)
        $sorted = $valueCounts->sortDesc();

        return [
            'labels' => $sorted->keys()->toArray(),
            'values' => $sorted->values()->toArray(),
        ];
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


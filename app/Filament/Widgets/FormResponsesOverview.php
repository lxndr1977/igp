<?php

namespace App\Filament\Widgets;

use App\Models\FormResponse;
use App\Models\FormTemplate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class FormResponsesOverview extends BaseWidget
{
   // Em App\Filament\Pages\Dashboard.php ou config/filament.php
public static function canView(): bool
{
    return false;
}

    protected function getStats(): array
    {
        // Total de respostas
        $totalResponses = FormResponse::count();
        
        // Respostas deste mês
        $thisMonthResponses = FormResponse::whereMonth('submitted_at', now()->month)
            ->whereYear('submitted_at', now()->year)
            ->count();
            
        // Respostas desta semana
        $thisWeekResponses = FormResponse::whereBetween('submitted_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        
        // Formulários ativos
        $activeForms = FormTemplate::where('is_active', true)->count();
        
        // Taxa de crescimento mensal
        $lastMonthResponses = FormResponse::whereMonth('submitted_at', now()->subMonth()->month)
            ->whereYear('submitted_at', now()->subMonth()->year)
            ->count();
            
        $monthlyGrowth = $lastMonthResponses > 0 
            ? round((($thisMonthResponses - $lastMonthResponses) / $lastMonthResponses) * 100, 1)
            : 0;

        return [
            Stat::make('Total de Respostas', $totalResponses)
                ->description('Todas as respostas recebidas')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
                
            Stat::make('Respostas Este Mês', $thisMonthResponses)
                ->description($monthlyGrowth >= 0 ? "+{$monthlyGrowth}% em relação ao mês anterior" : "{$monthlyGrowth}% em relação ao mês anterior")
                ->descriptionIcon($monthlyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyGrowth >= 0 ? 'success' : 'danger'),
                
            Stat::make('Respostas Esta Semana', $thisWeekResponses)
                ->description('Últimos 7 dias')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
                
            Stat::make('Formulários Ativos', $activeForms)
                ->description('Formulários disponíveis')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
        ];
    }
}


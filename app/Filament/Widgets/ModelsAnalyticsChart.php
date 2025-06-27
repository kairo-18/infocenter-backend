<?php

namespace App\Filament\Widgets;

use App\Models\Fire;
use App\Models\Flood;
use App\Models\Garbage;
use App\Models\Traffic;
use App\Models\Tsunami;
use App\Models\Utility;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ModelsAnalyticsChart extends ChartWidget
{
    protected static ?string $heading = 'Models Analytics';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'month' => 'Last 12 Months',
            'quarter' => 'Last 8 Quarters (6 months each)',
            'year' => 'Last 5 Years',
        ];
    }

    protected function getData(): array
    {
        $models = [
            'Fire' => Fire::class,
            'Flood' => Flood::class,
            'Garbage' => Garbage::class,
            'Traffic' => Traffic::class,
            'Tsunami' => Tsunami::class,
            'Utility' => Utility::class,
            'User Alert Registration' => \App\Models\SmsAlertRegistration::class,
        ];

        $data = [];
        $labels = [];

        switch ($this->filter) {
            case 'month':
                $data = $this->getMonthlyData($models);
                $labels = $this->getMonthlyLabels();
                break;
            case 'quarter':
                $data = $this->getQuarterlyData($models);
                $labels = $this->getQuarterlyLabels();
                break;
            case 'year':
                $data = $this->getYearlyData($models);
                $labels = $this->getYearlyLabels();
                break;
        }

        return [
            'datasets' => $data,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getMonthlyData($models): array
    {
        $datasets = [];
        $colors = [
            '#ef4444', // red
            '#3b82f6', // blue
            '#10b981', // green
            '#f59e0b', // yellow
            '#8b5cf6', // purple
            '#06b6d4', // cyan
            '#f97316', // pink
        ];

        $colorIndex = 0;

        foreach ($models as $modelName => $modelClass) {
            $monthlyData = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $count = $modelClass::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                $monthlyData[] = $count;
            }

            $datasets[] = [
                'label' => $modelName,
                'data' => $monthlyData,
                'borderColor' => $colors[$colorIndex % count($colors)],
                'backgroundColor' => $colors[$colorIndex % count($colors)].'20',
                'fill' => false,
                'tension' => 0.1,
            ];

            $colorIndex++;
        }

        return $datasets;
    }

    private function getQuarterlyData($models): array
    {
        $datasets = [];
        $colors = [
            '#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4',
        ];

        $colorIndex = 0;

        foreach ($models as $modelName => $modelClass) {
            $quarterlyData = [];

            for ($i = 7; $i >= 0; $i--) {
                $startDate = Carbon::now()->subMonths($i * 6)->startOfMonth();
                $endDate = Carbon::now()->subMonths($i * 6)->addMonths(6)->endOfMonth();

                $count = $modelClass::whereBetween('created_at', [$startDate, $endDate])
                    ->count();
                $quarterlyData[] = $count;
            }

            $datasets[] = [
                'label' => $modelName,
                'data' => $quarterlyData,
                'borderColor' => $colors[$colorIndex % count($colors)],
                'backgroundColor' => $colors[$colorIndex % count($colors)].'20',
                'fill' => false,
                'tension' => 0.1,
            ];

            $colorIndex++;
        }

        return $datasets;
    }

    private function getYearlyData($models): array
    {
        $datasets = [];
        $colors = [
            '#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4',
        ];

        $colorIndex = 0;

        foreach ($models as $modelName => $modelClass) {
            $yearlyData = [];

            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $count = $modelClass::whereYear('created_at', $year)->count();
                $yearlyData[] = $count;
            }

            $datasets[] = [
                'label' => $modelName,
                'data' => $yearlyData,
                'borderColor' => $colors[$colorIndex % count($colors)],
                'backgroundColor' => $colors[$colorIndex % count($colors)].'20',
                'fill' => false,
                'tension' => 0.1,
            ];

            $colorIndex++;
        }

        return $datasets;
    }

    private function getMonthlyLabels(): array
    {
        $labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subMonths($i)->format('M Y');
        }

        return $labels;
    }

    private function getQuarterlyLabels(): array
    {
        $labels = [];
        for ($i = 7; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonths($i * 6);
            $endDate = Carbon::now()->subMonths($i * 6)->addMonths(6);
            $labels[] = $startDate->format('M Y').' - '.$endDate->format('M Y');
        }

        return $labels;
    }

    private function getYearlyLabels(): array
    {
        $labels = [];
        for ($i = 4; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subYears($i)->format('Y');
        }

        return $labels;
    }
}

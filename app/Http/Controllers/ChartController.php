<?php

namespace App\Http\Controllers;

use App\Models\Fire;
use App\Models\Flood;
use App\Models\Garbage;
use App\Models\Traffic;
use App\Models\Tsunami;
use App\Models\Utility;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ChartController extends Controller
{
    private array $models = [
        'Fire' => Fire::class,
        'Flood' => Flood::class,
        'Garbage' => Garbage::class,
        'Traffic' => Traffic::class,
        'Tsunami' => Tsunami::class,
        'Utility' => Utility::class,
        'User Alert Registration' => \App\Models\SmsAlertRegistration::class,
    ];

    private array $colors = [
        '#ef4444', // red
        '#3b82f6', // blue
        '#10b981', // green
        '#f59e0b', // yellow
        '#8b5cf6', // purple
        '#06b6d4', // cyan
        '#f97316', // orange
    ];

    public function downloadPdf(Request $request)
    {
        $filter = $request->get('filter', 'month');

        // Validate filter
        if (! in_array($filter, ['month', 'quarter', 'year'])) {
            $filter = 'month';
        }

        $data = $this->getChartData($filter);

        // Generate chart image
        $chartImageUrl = $this->generateChartImage($data['datasets'], $data['labels'], $filter);

        $html = view('pdf.chart', [
            'datasets' => $data['datasets'],
            'labels' => $data['labels'],
            'heading' => 'Analytics',
            'filter' => $filter,
            'filterLabel' => $this->getFilterLabel($filter),
            'colors' => $this->colors,
            'chartImageUrl' => $chartImageUrl,
        ])->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true);

        return $pdf->download("models-analytics-{$filter}-".now()->format('Y-m-d').'.pdf');
    }

    public function downloadCustomDatePdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $data = $this->getCustomDateRangeData($startDate, $endDate);

        // Generate chart image
        $chartImageUrl = $this->generateChartImage($data['datasets'], $data['labels'], 'custom');

        $filterLabel = "From {$startDate->format('M j, Y')} to {$endDate->format('M j, Y')}";

        $html = view('pdf.chart', [
            'datasets' => $data['datasets'],
            'labels' => $data['labels'],
            'heading' => 'Analytics',
            'filter' => 'custom',
            'filterLabel' => $filterLabel,
            'colors' => $this->colors,
            'chartImageUrl' => $chartImageUrl,
        ])->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true);

        $filename = "models-analytics-custom-{$startDate->format('Y-m-d')}-to-{$endDate->format('Y-m-d')}.pdf";

        return $pdf->download($filename);
    }

    private function getCustomDateRangeData(Carbon $startDate, Carbon $endDate): array
    {
        $datasets = [];
        $colorIndex = 0;

        // Calculate the number of days and determine appropriate grouping
        $daysDiff = $startDate->diffInDays($endDate);

        if ($daysDiff <= 31) {
            // Daily grouping for periods up to 31 days
            return $this->getDailyData($startDate, $endDate);
        } elseif ($daysDiff <= 365) {
            // Weekly grouping for periods up to 1 year
            return $this->getWeeklyData($startDate, $endDate);
        } else {
            // Monthly grouping for periods over 1 year
            return $this->getMonthlyDataCustomRange($startDate, $endDate);
        }
    }

    private function getDailyData(Carbon $startDate, Carbon $endDate): array
    {
        $datasets = [];
        $labels = [];
        $colorIndex = 0;

        // Generate labels (dates)
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $labels[] = $currentDate->format('M j');
            $currentDate->addDay();
        }

        foreach ($this->models as $modelName => $modelClass) {
            $dailyData = [];
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $count = $modelClass::whereDate('created_at', $currentDate)->count();
                $dailyData[] = $count;
                $currentDate->addDay();
            }

            $datasets[] = [
                'label' => $modelName,
                'data' => $dailyData,
                'borderColor' => $this->colors[$colorIndex % count($this->colors)],
                'backgroundColor' => $this->colors[$colorIndex % count($this->colors)].'20',
                'total' => array_sum($dailyData),
            ];

            $colorIndex++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    private function getWeeklyData(Carbon $startDate, Carbon $endDate): array
    {
        $datasets = [];
        $labels = [];
        $colorIndex = 0;

        // Generate weekly periods
        $currentDate = $startDate->copy()->startOfWeek();
        while ($currentDate->lt($endDate)) {
            $weekEnd = $currentDate->copy()->endOfWeek();
            if ($weekEnd->gt($endDate)) {
                $weekEnd = $endDate->copy();
            }

            $labels[] = $currentDate->format('M j').' - '.$weekEnd->format('M j');
            $currentDate->addWeek();
        }

        foreach ($this->models as $modelName => $modelClass) {
            $weeklyData = [];
            $currentDate = $startDate->copy()->startOfWeek();

            while ($currentDate->lt($endDate)) {
                $weekEnd = $currentDate->copy()->endOfWeek();
                if ($weekEnd->gt($endDate)) {
                    $weekEnd = $endDate->copy();
                }

                $count = $modelClass::whereBetween('created_at', [$currentDate, $weekEnd])->count();
                $weeklyData[] = $count;
                $currentDate->addWeek();
            }

            $datasets[] = [
                'label' => $modelName,
                'data' => $weeklyData,
                'borderColor' => $this->colors[$colorIndex % count($this->colors)],
                'backgroundColor' => $this->colors[$colorIndex % count($this->colors)].'20',
                'total' => array_sum($weeklyData),
            ];

            $colorIndex++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    private function getMonthlyDataCustomRange(Carbon $startDate, Carbon $endDate): array
    {
        $datasets = [];
        $labels = [];
        $colorIndex = 0;

        // Generate monthly periods
        $currentDate = $startDate->copy()->startOfMonth();
        while ($currentDate->lte($endDate)) {
            $monthEnd = $currentDate->copy()->endOfMonth();
            if ($monthEnd->gt($endDate)) {
                $monthEnd = $endDate->copy();
            }

            $labels[] = $currentDate->format('M Y');
            $currentDate->addMonth();
        }

        foreach ($this->models as $modelName => $modelClass) {
            $monthlyData = [];
            $currentDate = $startDate->copy()->startOfMonth();

            while ($currentDate->lte($endDate)) {
                $monthEnd = $currentDate->copy()->endOfMonth();
                if ($monthEnd->gt($endDate)) {
                    $monthEnd = $endDate->copy();
                }

                $count = $modelClass::whereBetween('created_at', [$currentDate, $monthEnd])->count();
                $monthlyData[] = $count;
                $currentDate->addMonth();
            }

            $datasets[] = [
                'label' => $modelName,
                'data' => $monthlyData,
                'borderColor' => $this->colors[$colorIndex % count($this->colors)],
                'backgroundColor' => $this->colors[$colorIndex % count($this->colors)].'20',
                'total' => array_sum($monthlyData),
            ];

            $colorIndex++;
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    private function generateChartImage(array $datasets, array $labels, string $filter): string
    {
        // Prepare datasets for Chart.js
        $chartDatasets = [];
        foreach ($datasets as $dataset) {
            $chartDatasets[] = [
                'label' => $dataset['label'],
                'data' => $dataset['data'],
                'borderColor' => $dataset['borderColor'],
                'backgroundColor' => $dataset['backgroundColor'],
                'borderWidth' => 3,
                'fill' => false,
                'tension' => 0.4,
                'pointBackgroundColor' => $dataset['borderColor'],
                'pointBorderColor' => '#ffffff',
                'pointBorderWidth' => 2,
                'pointRadius' => 5,
            ];
        }

        $chartTitle = $filter === 'custom' ? 'Models Analytics - Custom Date Range' : 'Models Analytics - '.$this->getFilterLabel($filter);

        $chartConfig = [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => $chartDatasets,
            ],
            'options' => [
                'responsive' => false,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => $chartTitle,
                        'font' => [
                            'size' => 18,
                            'weight' => 'bold',
                        ],
                        'color' => '#2563eb',
                    ],
                    'legend' => [
                        'display' => true,
                        'position' => 'bottom',
                        'labels' => [
                            'usePointStyle' => true,
                            'padding' => 20,
                            'font' => [
                                'size' => 12,
                            ],
                        ],
                    ],
                ],
                'scales' => [
                    'x' => [
                        'display' => true,
                        'title' => [
                            'display' => true,
                            'text' => 'Time Period',
                            'font' => [
                                'size' => 14,
                                'weight' => 'bold',
                            ],
                            'color' => '#64748b',
                        ],
                        'ticks' => [
                            'maxRotation' => 45,
                            'color' => '#64748b',
                        ],
                        'grid' => [
                            'color' => '#f0f0f0',
                        ],
                    ],
                    'y' => [
                        'display' => true,
                        'title' => [
                            'display' => true,
                            'text' => 'Number of Incidents',
                            'font' => [
                                'size' => 14,
                                'weight' => 'bold',
                            ],
                            'color' => '#64748b',
                        ],
                        'beginAtZero' => true,
                        'ticks' => [
                            'color' => '#64748b',
                        ],
                        'grid' => [
                            'color' => '#e5e5e5',
                        ],
                    ],
                ],
            ],
        ];

        // Use QuickChart API to generate chart image
        $quickChartUrl = 'https://quickchart.io/chart';
        $chartUrl = $quickChartUrl.'?'.http_build_query([
            'chart' => json_encode($chartConfig),
            'width' => 800,
            'height' => 400,
            'format' => 'png',
            'backgroundColor' => 'white',
        ]);

        return $chartUrl;
    }

    // Keep existing methods for backward compatibility
    private function getChartData(string $filter): array
    {
        switch ($filter) {
            case 'month':
                return [
                    'datasets' => $this->getMonthlyData(),
                    'labels' => $this->getMonthlyLabels(),
                ];
            case 'quarter':
                return [
                    'datasets' => $this->getQuarterlyData(),
                    'labels' => $this->getQuarterlyLabels(),
                ];
            case 'year':
                return [
                    'datasets' => $this->getYearlyData(),
                    'labels' => $this->getYearlyLabels(),
                ];
            default:
                return [
                    'datasets' => $this->getMonthlyData(),
                    'labels' => $this->getMonthlyLabels(),
                ];
        }
    }

    private function getMonthlyData(): array
    {
        $datasets = [];
        $colorIndex = 0;

        foreach ($this->models as $modelName => $modelClass) {
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
                'borderColor' => $this->colors[$colorIndex % count($this->colors)],
                'backgroundColor' => $this->colors[$colorIndex % count($this->colors)].'20',
                'total' => array_sum($monthlyData),
            ];

            $colorIndex++;
        }

        return $datasets;
    }

    private function getQuarterlyData(): array
    {
        $datasets = [];
        $colorIndex = 0;

        foreach ($this->models as $modelName => $modelClass) {
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
                'borderColor' => $this->colors[$colorIndex % count($this->colors)],
                'backgroundColor' => $this->colors[$colorIndex % count($this->colors)].'20',
                'total' => array_sum($quarterlyData),
            ];

            $colorIndex++;
        }

        return $datasets;
    }

    private function getYearlyData(): array
    {
        $datasets = [];
        $colorIndex = 0;

        foreach ($this->models as $modelName => $modelClass) {
            $yearlyData = [];

            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $count = $modelClass::whereYear('created_at', $year)->count();
                $yearlyData[] = $count;
            }

            $datasets[] = [
                'label' => $modelName,
                'data' => $yearlyData,
                'borderColor' => $this->colors[$colorIndex % count($this->colors)],
                'backgroundColor' => $this->colors[$colorIndex % count($this->colors)].'20',
                'total' => array_sum($yearlyData),
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

    private function getFilterLabel(string $filter): string
    {
        return match ($filter) {
            'month' => 'Last 12 Months',
            'quarter' => 'Last 8 Quarters (6 months each)',
            'year' => 'Last 5 Years',
            default => 'Last 12 Months',
        };
    }

    public function getChartSummary(string $filter): array
    {
        $data = $this->getChartData($filter);
        $summary = [];

        foreach ($data['datasets'] as $dataset) {
            $summary[$dataset['label']] = [
                'total' => $dataset['total'],
                'average' => round($dataset['total'] / count($dataset['data']), 2),
                'max' => max($dataset['data']),
                'min' => min($dataset['data']),
            ];
        }

        return $summary;
    }
}

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
    ];

    private array $colors = [
        '#ef4444', // red
        '#3b82f6', // blue
        '#10b981', // green
        '#f59e0b', // yellow
        '#8b5cf6', // purple
        '#06b6d4', // cyan
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
            'heading' => 'Models Analytics',
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
                        'text' => 'Models Analytics - '.$this->getFilterLabel($filter),
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

    // Alternative method using local chart generation (requires node.js)
    private function generateChartImageLocal(array $datasets, array $labels, string $filter): string
    {
        // This method would require a Node.js service or puppeteer
        // For now, we'll use the QuickChart API method above
        return $this->generateChartImage($datasets, $labels, $filter);
    }

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

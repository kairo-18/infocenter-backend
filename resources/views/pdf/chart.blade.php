<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $heading }} - {{ $filterLabel }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e5e5;
        }

        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 28px;
        }

        .header h2 {
            color: #64748b;
            margin: 5px 0 0 0;
            font-size: 18px;
            font-weight: normal;
        }

        .chart-container {
            margin: 30px 0;
            text-align: center;
        }

        .chart-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            background: white;
            margin: 0 auto;
            display: block;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .summary-table th {
            background: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .summary-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e5e5;
        }

        .summary-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .model-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e5e5e5;
            padding-top: 20px;
            visibility:hidden;
        }

        .data-points {
            font-size: 12px;
            color: #64748b;
            margin-top: 10px;
        }

        .highlight-box {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .highlight-box h3 {
            color: #1e40af;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        /* Print optimization */
        @media print {
            .chart-container {
                page-break-inside: avoid;
            }

            .summary-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $heading }}</h1>
        <h2>{{ $filterLabel }}</h2>
        <div class="data-points">
            Generated on {{ now()->format('F j, Y \a\t g:i A') }}
        </div>
    </div>

    <div class="chart-container">
        <img src="{{ $chartImageUrl }}" alt="Analytics Chart" class="chart-image" />
    </div>

    <div class="highlight-box">
        <h3>Data Summary</h3>
        <p>This chart shows the distribution of incidents across different categories over the selected time period.</p>
    </div>

    <!-- Summary Statistics Table -->
    <table class="summary-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Total Incidents</th>
                <th>Average per Period</th>
                <th>Highest Count</th>
                <th>Lowest Count</th>
                <th>Latest Period</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datasets as $index => $dataset)
                <tr>
                    <td>
                        <span class="model-color" style="background-color: {{ $dataset['borderColor'] }};"></span>
                        {{ $dataset['label'] }}
                    </td>
                    <td>{{ number_format($dataset['total']) }}</td>
                    <td>{{ number_format($dataset['total'] / count($dataset['data']), 1) }}</td>
                    <td>{{ number_format(max($dataset['data'])) }}</td>
                    <td>{{ number_format(min($dataset['data'])) }}</td>
                    <td>{{ number_format(end($dataset['data'])) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Summary -->
    <div class="highlight-box">
        <h3>Overall Statistics</h3>
        @php
            $grandTotal = array_sum(array_column($datasets, 'total'));
            $avgPerPeriod = $grandTotal / count($labels);
        @endphp
        <p><strong>Total Incidents:</strong> {{ number_format($grandTotal) }}</p>
        <p><strong>Average per Period:</strong> {{ number_format($avgPerPeriod, 1) }}</p>
        <p><strong>Most Active Category:</strong>
            @php
                $maxDataset = collect($datasets)->sortByDesc('total')->first();
            @endphp
            {{ $maxDataset['label'] }} ({{ number_format($maxDataset['total']) }} incidents)
        </p>
    </div>

    <div class="footer">
        <p>Analytics Report | Generated from Filament Dashboard</p>
        <p>Report covers {{ $filterLabel }} ending {{ now()->format('F j, Y') }}</p>
    </div>
</body>
</html>

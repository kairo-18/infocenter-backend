<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DatePickerDownload extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.date-picker-download';

    protected static ?int $sort = 3;

    // increase width
    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->subMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Custom Date Range Report')
                    ->description('Select a date range to generate a custom analytics report')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required()
                            ->maxDate(fn () => $this->data['end_date'] ?? now())
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                if ($state && isset($this->data['end_date'])) {
                                    $startDate = Carbon::parse($state);
                                    $endDate = Carbon::parse($this->data['end_date']);

                                    if ($startDate->gt($endDate)) {
                                        $this->form->fill([
                                            'start_date' => $state,
                                            'end_date' => $startDate->format('Y-m-d'),
                                        ]);
                                    }
                                }
                            }),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->required()
                            ->minDate(fn () => $this->data['start_date'] ?? now()->subYear())
                            ->maxDate(now())
                            ->reactive(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function downloadPdf(): void
    {
        $data = $this->form->getState();

        // Validate the form data
        if (empty($data['start_date']) || empty($data['end_date'])) {
            Notification::make()
                ->title('Error')
                ->body('Please select both start and end dates.')
                ->danger()
                ->send();

            return;
        }

        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        // Validate date range
        if ($startDate->gt($endDate)) {
            Notification::make()
                ->title('Error')
                ->body('Start date must be before or equal to end date.')
                ->danger()
                ->send();

            return;
        }

        // Check if date range is not too large (optional)
        if ($startDate->diffInDays($endDate) > 365) {
            Notification::make()
                ->title('Warning')
                ->body('Date range is longer than 1 year. The report might take longer to generate.')
                ->warning()
                ->send();
        }

        try {
            // Show loading notification
            Notification::make()
                ->title('Generating Report')
                ->body('Your PDF report is being generated...')
                ->info()
                ->send();

            // Redirect to the download route
            $this->redirect(route('chart.download.custom', [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]));
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Failed to generate report: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadPdfJs(): void
    {
        $data = $this->form->getState();

        // Validate the form data
        if (empty($data['start_date']) || empty($data['end_date'])) {
            Notification::make()
                ->title('Error')
                ->body('Please select both start and end dates.')
                ->danger()
                ->send();

            return;
        }

        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        // Validate date range
        if ($startDate->gt($endDate)) {
            Notification::make()
                ->title('Error')
                ->body('Start date must be before or equal to end date.')
                ->danger()
                ->send();

            return;
        }

        // Check if date range is not too large (optional)
        if ($startDate->diffInDays($endDate) > 365) {
            Notification::make()
                ->title('Warning')
                ->body('Date range is longer than 1 year. The report might take longer to generate.')
                ->warning()
                ->send();
        }

        // Generate download URL
        $downloadUrl = route('chart.download.custom', [
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);

        // Use JavaScript to trigger download
        $this->dispatch('download-file', url: $downloadUrl);
    }

    public function getDateRangeInfo(): string
    {
        if (empty($this->data['start_date']) || empty($this->data['end_date'])) {
            return '';
        }

        $startDate = Carbon::parse($this->data['start_date']);
        $endDate = Carbon::parse($this->data['end_date']);
        $daysDiff = $startDate->diffInDays($endDate) + 1;

        if ($daysDiff <= 31) {
            return "Daily breakdown ({$daysDiff} days)";
        } elseif ($daysDiff <= 365) {
            $weeks = ceil($daysDiff / 7);

            return "Weekly breakdown (≈{$weeks} weeks)";
        } else {
            $months = $startDate->diffInMonths($endDate) + 1;

            return "Monthly breakdown (≈{$months} months)";
        }
    }
}

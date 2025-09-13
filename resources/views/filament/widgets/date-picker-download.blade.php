<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Custom Date Range Analytics
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Generate a detailed analytics report for your selected date range
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-calendar class="w-5 h-5 text-gray-400" />
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $this->getDateRangeInfo() }}
                    </span>
                </div>
            </div>

            <form wire:submit="downloadPdf">
                {{ $this->form }}

                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        @if(!empty($this->data['start_date']) && !empty($this->data['end_date']))
                            @php
                                $start = \Carbon\Carbon::parse($this->data['start_date']);
                                $end = \Carbon\Carbon::parse($this->data['end_date']);
                                $days = $start->diffInDays($end) + 1;
                            @endphp
                            <div class="flex items-center space-x-4">
                                <span>📊 Report will include {{ $days }} day{{ $days > 1 ? 's' : '' }} of data</span>
                                <span>📅 {{ $start->format('M j, Y') }} - {{ $end->format('M j, Y') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-3">
                        <x-filament::button
                            type="submit"
                            color="primary"
                            icon="heroicon-o-arrow-down-tray"
                            :disabled="empty($this->data['start_date']) || empty($this->data['end_date'])"
                        >
                            Download Custom PDF Report
                        </x-filament::button>
                    </div>
                </div>
            </form>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5" />
                    <div class="text-sm text-blue-700 dark:text-blue-300">
                        <p class="font-medium mb-1">Report Details:</p>
                        <ul class="space-y-1 text-xs">
                            <li>• Data will be grouped automatically based on date range</li>
                            <li>• ≤31 days: Daily breakdown</li>
                            <li>• ≤365 days: Weekly breakdown</li>
                            <li>• >365 days: Monthly breakdown</li>
                            <li>• All incident categories will be included in the analysis</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


<div class="flex bg-black justify-end p-4 bg-white rounded-lg shadow">
    <div class="relative mx-auto inline-block text-left" x-data="{ open: false }">
        <!-- Dropdown Button -->
        <h1 class="text-center mb-4">
            <span class="text-lg font-semibold text-gray-900 dark:text-white">Download Chart as PDF</span>
        </h1>
        <button
            @click="open = !open"
            type="button"
            class="inline-flex items-center justify-center py-2 px-4 gap-2 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-page-button-action bg-primary-600 text-white hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 focus:ring-white"
            :aria-expanded="open"
            aria-haspopup="true"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046 3.5 3.5 0 014.504 4.272A4 4 0 0115 17H5.5zm5.25-9.25a.75.75 0 00-1.5 0v4.59l-1.95-2.1a.75.75 0 10-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 00-1.1-1.02l-1.95 2.1V7.75z" clip-rule="evenodd" />
            </svg>
            <span>Click to choose timeline</span>
            <svg class="w-5 h-5 ml-2 -mr-1 transform transition-transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            @click.away="open = false"
            class="absolute right-0 z-10 mt-2 w-64 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
        >
            <div class="py-1" role="none">
                <a
                    href="{{ route('chart.download.pdf', ['filter' => 'month']) }}"
                    target="_blank"
                    @click="open = false"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                    role="menuitem"
                >
                    <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    Last 12 Months
                </a>
                <a
                    href="{{ route('chart.download.pdf', ['filter' => 'quarter']) }}"
                    target="_blank"
                    @click="open = false"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                    role="menuitem"
                >
                    <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    Last 8 Quarters (6 months each)
                </a>
                <a
                    href="{{ route('chart.download.pdf', ['filter' => 'year']) }}"
                    target="_blank"
                    @click="open = false"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                    role="menuitem"
                >
                    <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-18 0v-1.5" />
                    </svg>
                    Last 5 Years
                </a>
            </div>
        </div>
    </div>
</div>
    </x-filament::section>
</x-filament-widgets::widget>

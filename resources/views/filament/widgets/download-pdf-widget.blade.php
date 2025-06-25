<div class="flex justify-end p-4 bg-white rounded-lg shadow">
    <a
        href="{{ route('chart.download.pdf', ['filter' => $this->filter ?? 'month']) }}"
        target="_blank"
        class="filament-button filament-button-size-md inline-flex items-center justify-center py-2 gap-2 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-page-button-action bg-primary-600 text-white hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 focus:ring-white"
    >
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046 3.5 3.5 0 014.504 4.272A4 4 0 0115 17H5.5zm5.25-9.25a.75.75 0 00-1.5 0v4.59l-1.95-2.1a.75.75 0 10-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 00-1.1-1.02l-1.95 2.1V7.75z" clip-rule="evenodd" />
        </svg>
        <span>Download Chart as PDF</span>
    </a>
</div>

<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DownloadPdfWidget extends Widget
{
    protected static string $view = 'filament.widgets.download-pdf-widget';

    protected static ?int $sort = 2; // Place it near your chart widget

    protected int|string|array $columnSpan = 'full';
}

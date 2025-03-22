<?php

namespace App\Filament\Resources\FirstAidResource\Pages;

use App\Filament\Resources\FirstAidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFirstAids extends ListRecords
{
    protected static string $resource = FirstAidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\GarbageResource\Pages;

use App\Filament\Resources\GarbageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGarbages extends ListRecords
{
    protected static string $resource = GarbageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

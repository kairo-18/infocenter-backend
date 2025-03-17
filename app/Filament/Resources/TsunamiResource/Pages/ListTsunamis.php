<?php

namespace App\Filament\Resources\TsunamiResource\Pages;

use App\Filament\Resources\TsunamiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTsunamis extends ListRecords
{
    protected static string $resource = TsunamiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

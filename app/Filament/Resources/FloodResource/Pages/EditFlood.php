<?php

namespace App\Filament\Resources\FloodResource\Pages;

use App\Filament\Resources\FloodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFlood extends EditRecord
{
    protected static string $resource = FloodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

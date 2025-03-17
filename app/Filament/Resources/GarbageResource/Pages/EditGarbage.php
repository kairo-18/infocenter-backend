<?php

namespace App\Filament\Resources\GarbageResource\Pages;

use App\Filament\Resources\GarbageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGarbage extends EditRecord
{
    protected static string $resource = GarbageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

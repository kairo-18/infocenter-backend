<?php

namespace App\Filament\Resources\FireResource\Pages;

use App\Filament\Resources\FireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFire extends EditRecord
{
    protected static string $resource = FireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

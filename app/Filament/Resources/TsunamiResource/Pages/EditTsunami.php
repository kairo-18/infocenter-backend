<?php

namespace App\Filament\Resources\TsunamiResource\Pages;

use App\Filament\Resources\TsunamiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTsunami extends EditRecord
{
    protected static string $resource = TsunamiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

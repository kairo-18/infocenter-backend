<?php

namespace App\Filament\Resources\SmsAlertRegistrationResource\Pages;

use App\Filament\Resources\SmsAlertRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmsAlertRegistrations extends ListRecords
{
    protected static string $resource = SmsAlertRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

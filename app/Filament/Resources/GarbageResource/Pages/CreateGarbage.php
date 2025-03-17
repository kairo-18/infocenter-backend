<?php

namespace App\Filament\Resources\GarbageResource\Pages;

use App\Filament\Resources\GarbageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGarbage extends CreateRecord
{
    protected static string $resource = GarbageResource::class;
}

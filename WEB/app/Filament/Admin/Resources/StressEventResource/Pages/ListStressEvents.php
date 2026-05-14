<?php

namespace App\Filament\Admin\Resources\StressEventResource\Pages;

use App\Filament\Admin\Resources\StressEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStressEvents extends ListRecords
{
    protected static string $resource = StressEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

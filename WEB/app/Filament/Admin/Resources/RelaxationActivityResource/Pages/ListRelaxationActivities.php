<?php

namespace App\Filament\Admin\Resources\RelaxationActivityResource\Pages;

use App\Filament\Admin\Resources\RelaxationActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelaxationActivities extends ListRecords
{
    protected static string $resource = RelaxationActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

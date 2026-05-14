<?php

namespace App\Filament\Admin\Resources\InformationResource\Pages;

use App\Filament\Admin\Resources\InformationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInformation extends ListRecords
{
    protected static string $resource = InformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

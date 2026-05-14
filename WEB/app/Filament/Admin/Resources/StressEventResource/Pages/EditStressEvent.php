<?php

namespace App\Filament\Admin\Resources\StressEventResource\Pages;

use App\Filament\Admin\Resources\StressEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStressEvent extends EditRecord
{
    protected static string $resource = StressEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

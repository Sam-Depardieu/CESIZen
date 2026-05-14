<?php

namespace App\Filament\Admin\Resources\RelaxationActivityResource\Pages;

use App\Filament\Admin\Resources\RelaxationActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelaxationActivity extends EditRecord
{
    protected static string $resource = RelaxationActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

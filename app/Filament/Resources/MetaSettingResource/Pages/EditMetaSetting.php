<?php

namespace App\Filament\Resources\MetaSettingResource\Pages;

use App\Filament\Resources\MetaSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetaSetting extends EditRecord
{
    protected static string $resource = MetaSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

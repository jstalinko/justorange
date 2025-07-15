<?php

namespace App\Filament\Resources\MetaSettingResource\Pages;

use App\Filament\Resources\MetaSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetaSettings extends ListRecords
{
    protected static string $resource = MetaSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

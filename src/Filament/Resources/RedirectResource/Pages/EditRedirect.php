<?php

namespace Dashed\DashedCore\Filament\Resources\RedirectResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Dashed\DashedCore\Filament\Resources\RedirectResource;

class EditRedirect extends EditRecord
{
    protected static string $resource = RedirectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

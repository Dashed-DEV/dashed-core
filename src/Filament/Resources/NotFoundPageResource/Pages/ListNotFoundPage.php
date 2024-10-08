<?php

namespace Dashed\DashedCore\Filament\Resources\NotFoundPageResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Dashed\DashedCore\Filament\Resources\NotFoundPageResource;

class ListNotFoundPage extends ListRecords
{
    protected static string $resource = NotFoundPageResource::class;

    public ?string $tableSortColumn = 'last_occurrence';

    public ?string $tableSortDirection = 'desc';

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \Dashed\DashedCore\Filament\Widgets\NotFoundPageGlobalStats::class,
        ];
    }
}

<?php

namespace Dashed\DashedCore\Classes;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class LinkHelper
{
    public static function field($prefix = 'url', $required = false)
    {
        $routeModels = [];
        $routeModelInputs = [];
        foreach (cms()->builder('routeModels') as $key => $routeModel) {
            $routeModels[$key] = $routeModel['name'];

            $routeModelInputs[] =
                Select::make("{$prefix}_{$key}_id")
                    ->label("Kies een " . strtolower($routeModel['name']))
                    ->required($required)
                    ->options($routeModel['class']::pluck($routeModel['nameField'] ?: 'name', 'id'))
                    ->searchable()
                    ->visible(fn ($get) => in_array($get("{$prefix}_type"), [$key]));
        }

        return Group::make(array_merge([
            Select::make("{$prefix}_type")
                ->label('Type voor ' . $prefix)
                ->default('normal')
                ->options(array_merge([
                    'normal' => 'Normaal',
                ], $routeModels))
                ->reactive()
                ->required($required),
            TextInput::make("{$prefix}_url")
                ->label('Url')
                ->required($required)
                ->placeholder('Example: https://example.com of /contact')
                ->visible(fn ($get) => in_array($get("{$prefix}_type"), ['normal'])),
        ], $routeModelInputs))
            ->columns(2);
    }

    public static function getUrl(array $data = [], $prefix = 'url'): string
    {
        if (($data["{$prefix}_type"] ?? 'normal') == 'normal') {
            return $data["{$prefix}_url"] ?? '#';
        }

        $routeModel = cms()->builder('routeModels')[$data["{$prefix}_type"]];

        if (! $routeModel) {
            return '';
        }

        $record = $routeModel['class']::find($data["{$prefix}_{$data["{$prefix}_type"]}_id"]);

        return $record ? $record->getUrl() : '#';
    }
}

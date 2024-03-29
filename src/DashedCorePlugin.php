<?php

namespace Dashed\DashedCore;

use Filament\Panel;
use Filament\Contracts\Plugin;
use Dashed\DashedCore\Filament\Resources\UserResource;
use Dashed\DashedCore\Filament\Resources\RedirectResource;
use Dashed\DashedCore\Filament\Pages\Settings\SettingsPage;
use Dashed\DashedCore\Filament\Pages\Settings\GeneralSettingsPage;
use Dashed\DashedCore\Filament\Pages\Settings\MetadataSettingsPage;

class DashedCorePlugin implements Plugin
{
    public function getId(): string
    {
        return 'dashed-core';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                UserResource::class,
                RedirectResource::class,
            ])
            ->pages([
                SettingsPage::class,
                GeneralSettingsPage::class,
                MetadataSettingsPage::class,
            ]);
    }

    public function boot(Panel $panel): void
    {

    }
}

<?php

namespace Dashed\DashedCore\Filament\Resources\UserResource\Users;

use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\CreateRecord;
use Dashed\DashedCore\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        unset($data['password_confirmation']);

        return $data;
    }
}

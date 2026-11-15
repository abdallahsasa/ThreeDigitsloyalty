<?php

namespace App\Filament\Shop\Resources\Users\Pages;

use App\Filament\Shop\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}

<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    // if user is admin, then can edit all users
    // if user is not admin, then can only edit himself
    public function canEdit(): bool
    {
        return auth()->user()->is_admin || auth()->user()->id == $this->record->id;
    }

    public function canDelete(): bool
    {
        return auth()->user()->is_admin;
    }



    public function canUpdate(): bool
    {
        return auth()->user()->is_admin || auth()->user()->id == $this->record->id;
    }

    public function canView(): bool
    {
        return auth()->user()->is_admin || auth()->user()->id == $this->record->id;
    }

    public function canCreate(): bool
    {
        return auth()->user()->is_admin;
    }

    public function canSearch(): bool
    {
        return auth()->user()->is_admin;
    }

    public function canViewAny(): bool
    {
        return auth()->user()->is_admin;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

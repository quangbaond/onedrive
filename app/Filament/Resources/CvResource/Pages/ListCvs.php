<?php

namespace App\Filament\Resources\CvResource\Pages;

use App\Filament\Resources\CvResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use JetBrains\PhpStorm\NoReturn;

class ListCvs extends ListRecords
{
    protected static string $resource = CvResource::class;

    protected function getHeaderActions(): array
    {

        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getTableColumnAction(): string
    {
        return 'actions';
    }

    // get data from database
    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        if(auth()->user()->is_admin){
            $data = CvResource::getModel()::query();
        } else {
            $groups = auth()->user()->groups()->get()->pluck('name')->toArray();
            $data = CvResource::getModel()::query()->whereHas('groups', function ($query) use ($groups) {
                $query->whereIn('name', $groups);
                $query->orWhereNull('group_id');
            });
        }

        return $data;
    }
}

<?php

namespace App\Filament\Resources\CvResource\Pages;

use App\Filament\Resources\CvResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
            $group = auth()->user()->groups()->get()->pluck('name')->toArray();
            $data = CvResource::getModel()::query()->whereHas('groups', function ($query) use ($group) {
                $query->whereIn('name', $group);
                // or group = null
                $query->orWhereNull('group_id');
            });
        }

        return $data;
    }
}

<?php

namespace App\Filament\Resources\IndustryResource\Pages;

use App\Filament\Resources\IndustryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIndustry extends CreateRecord
{
    protected static string $resource = IndustryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = env('APP_URL'). '/posts/industry'. \Illuminate\Support\Str::slug($data['name']);

        return $data;
    }
}

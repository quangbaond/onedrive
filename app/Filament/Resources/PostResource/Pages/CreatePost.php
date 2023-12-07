<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        return $data;
    }
}

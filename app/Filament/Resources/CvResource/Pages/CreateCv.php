<?php

namespace App\Filament\Resources\CvResource\Pages;

use App\Filament\Resources\CvResource;
use App\Models\Config;
use App\Services\OneDriveService;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCv extends CreateRecord
{
    protected static string $resource = CvResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getRecord()->toArray();

        try{
            $path = storage_path('app/public/' . $data['cv']);
            $fileMimeType = \Illuminate\Support\Facades\File::mimeType($path);
            $oneDriveService = new OneDriveService();
            $accessToken = $oneDriveService->loginByRefreshToken();
            $client = new \GuzzleHttp\Client();
            $client->request(
                'PUT',
                'https://graph.microsoft.com/v1.0/me/drive/root:/cvs/'.$data['industry'] .'/' . $data['cv']. ':/content',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => $fileMimeType,
                    ],
                    'body' => file_get_contents($path),
                ]
            );
        } catch (\Exception $e) {
            Notification::make()
                ->title('Save CV to onedrive failed')
                ->danger()
                ->body('Please login to onedrive again. Click button below to login.')
                ->actions(actions: $this->actions($data))
                ->send();
        }
    }

    protected function actions (array $data): array
    {
        return [
            Action::make('Login onedrive')
                ->button()
                ->url(route('onedrive.login'), shouldOpenInNewTab: true),
        ];
    }
}

<?php

namespace App\Filament\Resources\CvResource\Pages;

use App\Filament\Resources\CvResource;
use App\Models\Config;
use App\Services\OneDriveService;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCv extends EditRecord
{
    protected static string $resource = CvResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();


        return $data;
    }

    protected function actions (array $data): array
    {
        return [
            Action::make('Login onedrive')
                ->button()
                ->url(route('onedrive.login'), shouldOpenInNewTab: true),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRecord()->toArray();

        try {
            $path = storage_path('app/public/' . $data['cv']);
            $fileMimeType = \Illuminate\Support\Facades\File::mimeType($path);
            $oneDriveService = new OneDriveService();
            $accessToken = $oneDriveService->loginByRefreshToken();
            // get industry
            $industry = $data['industry'];

            $fileName = $data['cv'];

            $client = new \GuzzleHttp\Client();
            $client->request(
                'PUT',
                'https://graph.microsoft.com/v1.0/me/drive/root:/cvs/'.$industry .'/' . $fileName . ':/content',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => $fileMimeType,
                    ],
                    'body' => file_get_contents($path),
                ]
            );
            Notification::make()
                ->title('Save CV to onedrive successfully')
                ->success()
                ->body('CV: ' . $data['cv'] . ' has been saved to onedrive successfully')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Save CV to onedrive failed')
                ->danger()
                ->body('Please login to onedrive again')
                ->actions(actions: $this->actions($data))
                ->send();
        }

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

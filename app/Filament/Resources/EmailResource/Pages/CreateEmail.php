<?php

namespace App\Filament\Resources\EmailResource\Pages;

use App\Filament\Resources\EmailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use http\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class CreateEmail extends CreateRecord
{
    protected static string $resource = EmailResource::class;

    protected  function mutateFormDataBeforeCreate(array $data): array
    {
        // send email by using the data
         if ($data['type'] == 'gmail') {
            $this->sendGmail($data);
        } else {
            $this->sendOutlook($data);
        }
         return $data;
    }

    protected function sendGmail($data): void
    {
        $address = explode(',' , $data['address']);
        foreach ($address as $key => $value) {
            Mail::send('emails.email', $data, function ($mail) use ($address, $data, $value) {
                $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $mail->to(trim($value));
                $mail->subject($data['subject']);
            });
        }
    }

    protected function sendOutlook($data): void
    {
         $accessToken = session()->get('accessToken');
         $address = explode(',' , $data['address']);

         $client = new \GuzzleHttp\Client();
         $url = 'https://graph.microsoft.com/v1.0/me/sendMail';
         foreach ($address as $key => $value) {
             $data['address'] = $value;
             $client->request('POST', $url, [
                 'headers' => [
                     'Authorization' => 'Bearer ' . $accessToken,
                 ],
                 'json' => [
                     "message" => [
                         "subject" => $data['subject'], // subject of mail
                         "body" => [
                             "contentType" => "HTML",
                                "content" => $data['content'] // content of mail
                         ],
                         "toRecipients" => [
                             [
                                 "emailAddress" => [
                                     "address" =>  $value// email of receiver
                                 ]
                             ]
                         ]
                     ]
                 ]
             ]);
         }
    }
}

<?php

namespace App\Services;

use App\Models\Config;

class OneDriveService
{
    public function loginByRefreshToken(): string
    {
        $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
        $params = [
            'client_id' => env('ONEDRIVE_CLIENT_ID'),
            'scope' => 'https://graph.microsoft.com/.default',
            'redirect_uri' => env('REDIRECT_URI'),
            'grant_type' => 'refresh_token',
            'client_secret' => env('ONEDRIVE_CLIENT_SECRET'),
            'refresh_token' => Config::query()->first()->refresh_token
        ];

        // call api
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'form_params' => $params
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        $accessToken = $response['access_token'];

        // save to db
        Config::query()->updateOrCreate(
            ['id' => 1],
            ['access_token' => $accessToken]
        );

        return $accessToken;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Microsoft\Graph\Generated\Applications\Item\Owners\GraphUser\GraphUserRequestBuilder;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Abstractions\ApiException;
use Microsoft\Kiota\Authentication\Oauth\AuthorizationCodeContext;


class OndriveController extends Controller
{
    public function index()
    {
        $params = [
            'client_id' => env('ONEDRIVE_CLIENT_ID'),
            'scope' => 'Files.Read Files.ReadWrite Files.Read.All Files.ReadWrite.All offline_access',
            'response_type' => 'code',
            'redirect_uri' => env('REDIRECT_URI'),
            'response_mode' => 'query',
            'grant_type' => 'authorization_code'
        ];

        $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?' . http_build_query($params);

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');
        $params = [
            'client_id' => env('ONEDRIVE_CLIENT_ID'),
            'scope' => 'https://graph.microsoft.com/.default',
            'code' => $code,
            'redirect_uri' => env('REDIRECT_URI'),
            'grant_type' => 'authorization_code',
            'client_secret' => env('ONEDRIVE_CLIENT_SECRET')
        ];

        try {
            $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', $params);
            $response = $response->json();
            $accessToken = $response['access_token'];
            session()->put('accessToken', $accessToken);
            Config::query()->updateOrCreate(
                ['id' => 1],
                ['access_token' => $accessToken, 'refresh_token' => $response['refresh_token']]
            );
            return "Done!";
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    // get drive function
    public function getDrive(Request $request)
    {
        $itemId = $request->query('itemId') ?? '';
        // dd($folder);
        $accessToken = session()->get('accessToken');

        $type = $request->query('itemId') ? 'items' : 'root';

        $url = 'https://graph.microsoft.com/v1.0/me/drive/' . $type . '/' . $itemId . '/children';
        // dd($url);
        //https://graph.microsoft.com/v1.0/me/drive/items/BCDE47833A75323F!134


        $response = Http::withHeaders(
            [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ]
        )->get($url);

        $response = $response->json();
        $values = $response['value'];
        return response()->json($values);
    }

    public function upload(Request $request)
    {
        // upload file to onedrive
        $file = $request->file('file');
        $accessToken = session()->get('accessToken');
        $fileName = $file->getClientOriginalName();


        $url = "https://graph.microsoft.com/v1.0/me/drive/root:/{$fileName}:/content";
        try {
            $response = Http::withHeaders(
                [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'multipart/form-data'
                ]
            )->put($url, $file);
            dd($response->json());
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function viewUpload()
    {
        return session()->get('accessToken');
//        return view('upload');
    }
}

<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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
            return "Done!";
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    // get drive function
    public function getDrive(Request $request)
    {
        // $itemId = $request->query('itemId') ?? '';
        // $accessToken = session()->get('accessToken');

        // $type = $request->query('itemId') ? 'items' : 'root';

        // $url = 'https://graph.microsoft.com/v1.0/me/drive/' . $type . '/' . $itemId . '/children';

        // $response = Http::withHeaders(
        //     [
        //         'Authorization' => 'Bearer ' . $accessToken,
        //         'Content-Type' => 'application/json'
        //     ]
        // )->get($url);

        // $response = $response->json();
        // $values = $response['value'];
        // return response()->json($values);
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $accessToken = session()->get('accessToken');
        $fileName = $file->getClientOriginalName();
        $fileMimeType = $file->getClientMimeType();

        $client = new Client();
        $response = $client->request(
            'PUT',
            'https://graph.microsoft.com/v1.0/me/drive/root:/cvs/' . $fileName . ':/content',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => $fileMimeType,
                ],
                'body' => file_get_contents($file->path()),
            ]
        );

        return  $response->getBody()->getContents()['status'];
        return redirect()->route('viewUpload');
    }

    public function viewUpload()
    {
        return view('upload');
    }

    public function download(Request $request)
    {
        $accessToken = session()->get('accessToken');
        $itemId = $request->query('itemId');
        $fileName = $request->query('fileName');
        $fileMimeType = $request->query('fileMimeType');

        $client = new Client();
        $response = $client->request(
            'GET',
            'https://graph.microsoft.com/v1.0/me/drive/items/' . $itemId . '/content',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => $fileMimeType,
                ],
            ]
        );

        Storage::disk('local')->put($fileName, $response->getBody()->getContents());
        return Storage::download($fileName);
    }

    public function sendMail(Request $request)
    {
        // $accessToken = session()->get('accessToken');
        // $client = new Client();
        // $url = 'https://graph.microsoft.com/v1.0/me/sendMail';

        // $response = $client->request('POST', $url, [
        //     'headers' => [
        //         'Authorization' => 'Bearer ' . $accessToken,
        //     ],
        //     'json' => [
        //         "message" => [
        //             "subject" => "Meet for lunch?",
        //             "body" => [
        //                 "contentType" => "Text",
        //                 "content" => "The new cafeteria is open." // content of mail
        //             ],
        //             "toRecipients" => [
        //                 [
        //                     "emailAddress" => [
        //                         "address" => "quangbaorp@gmail.com" // email of receiver
        //                     ]
        //                 ]
        //             ]
        //         ]
        //     ]
        // ]);
        // return $response->getBody()->getContents();
        Mail::send('mail', ['name' => 'Quang Bao'], function ($message) {
            $message->to('quangbaorp@gmail.com', 'Quang Bao')->subject('Test send mail');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
    }
}

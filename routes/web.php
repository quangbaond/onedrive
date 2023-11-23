<?php

use App\Http\Controllers\OndriveController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/onedrive', [OndriveController::class, 'index']);
Route::get('/onedrive/callback', [OndriveController::class, 'callback']);
Route::get('/onedrive/drive', [OndriveController::class, 'viewUpload'])->name('onedrive.upload_view');
Route::post('/onedrive/upload', [OndriveController::class, 'upload'])->name('onedrive.upload');
// send mail
Route::get('/send-mail', [OndriveController::class, 'sendMail'])->name('send.mail');

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

Route::get('/', [\App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{industry}', [\App\Http\Controllers\PostController::class, 'industry'])->name('posts.industry');
Route::get('/posts/detail/{slug}', [\App\Http\Controllers\PostController::class, 'slug'])->name('posts.show');
Route::get('/onedrive', [OndriveController::class, 'index'])->name('onedrive.login');
Route::get('/onedrive/callback', [OndriveController::class, 'callback']);
Route::get('/onedrive/drive', [OndriveController::class, 'getDrive'])->name('onedrive.upload_view');
Route::post('/onedrive/upload', [OndriveController::class, 'upload'])->name('onedrive.upload');

\Illuminate\Support\Facades\Auth::routes();


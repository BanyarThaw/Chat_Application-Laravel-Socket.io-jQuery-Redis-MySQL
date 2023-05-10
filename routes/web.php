<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageGroupController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('welcome'); });

Auth::routes([
    'confirm' => false,
    'reset' => false,
    'verify' => false,
]);

Route::group(['middleware' => 'admin'], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/conversation/{userId}',[MessageController::class,'conversation'])->name('message.conversation');
    Route::post('/send-message',[MessageController::class,'sendMessage'])->name('message.send-message');
    Route::post('/send-group-message',[MessageController::class,'sendGroupMessage'])->name('message.send-group-message');
    Route::resource('message-groups',MessageGroupController::class);
});
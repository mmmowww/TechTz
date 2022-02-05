<?php

use App\Http\Controllers\BotController;
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

Route::get('/', function () {
	return view('welcome');
});
Route::get('/create/{NameTool}', [BotController::class, 'createNewWrite']);
Route::get('/getallusser', [BotController::class, 'GetAllMessage']);
Route::get('/searchsubscribeuser', [BotController::class, 'SearchSubscribeUser']);
Route::get('/sendsubscribe', [BotController::class, 'SendSubcribeUser']);

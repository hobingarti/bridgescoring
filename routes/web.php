<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BoardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PertandinganController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);
Route::get('pertandingan/listPertandingan', [PertandinganController::class, 'listPertandingan']);
Route::get('pertandingan/{pertandingan}/managePlayers', [PertandinganController::class, 'managePlayers']);
Route::post('pertandingan/{pertandingan}/updatePlayers', [PertandinganController::class, 'updatePlayers']);
Route::get('pertandingan/{pertandingan}/boards', [PertandinganController::class, 'boards']);
Route::get('pertandingan/{pertandingan}/ranks', [PertandinganController::class, 'ranks']);
Route::get('pertandingan/{pertandingan}/ranksExcel', [PertandinganController::class, 'ranksExcel']);
Route::resource('pertandingan', PertandinganController::class);
Route::resource('board', BoardController::class);


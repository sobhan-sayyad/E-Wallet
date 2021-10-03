<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//PUBLIC ROUTES

Route::post('/createAccount',[UserController::class, 'createAccount']);
Route::post('/login',[UserController::class, 'login']);


//PROTECTED ROUTES
Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/walletHistory',[WalletController::class, 'history']);
    Route::post('/addFund',[WalletController::class, 'addFund']);
    Route::post('/withdraw',[WalletController::class, 'withdraw']);

    Route::get('/balance',[UserController::class, 'balance']);
    
    Route::post('/logout',[UserController::class, 'logout']);
});




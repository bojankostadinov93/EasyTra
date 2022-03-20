<?php

use App\Http\Controllers\CurrencyController;
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

//1.Create currencies table.
//2.Create api_key table with valid api keys
//3.Set Up first Api Request
//4.Integrate Fixer Api for currency data
//5.Insert in DB after success ping


Route::post("/currency-converter",[CurrencyController::class,"currencyConverter"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

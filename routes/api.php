<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

Route::middleware('auth:passport')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::group(['middleware' => 'json.only'], function ()
{
    /**  Signup */
    Route::post('signup', [AuthController::class, 'signup']);
    /**  Google Signin  */
    Route::post('signin/{provider}', [AuthController::class, 'oauth']);
    /** Signout*/
    Route::group(['middleware' => 'auth:api',], function ()
    {
        Route::post('signout', [AuthController::class, 'signout']);
    });
});
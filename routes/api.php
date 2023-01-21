<?php

use App\Http\Controllers\API\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

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
    /** Middleware Json Only */
    Route::group(['middleware' => 'json.only'], function (){
        /**  Signup */
        Route::post('signup', [AuthController::class, 'signup']);
        /**  Google Signin  */
        Route::post('signin/{provider}', [AuthController::class, 'oauth']);
        /** Signout*/
        /** Midleware Auth */
        Route::group(['middleware' => 'auth:api'], function ()
        {
            Route::apiResource('user', UserController::class)->only(['index']);
            Route::post('signout', [AuthController::class, 'signout']);
        });
        Route::apiResource('article', ArticleController::class);
    });
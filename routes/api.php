<?php

use App\Http\Controllers\API\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\TestController;
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
    Route::get('test', [TestController::class, 'index']);
    Route::get('datetime', function ()
    {
        return response()->json([Carbon\Carbon::now()->format('Y-m-d H:i')]);
    });
    /** Middleware Json Only */
    Route::group(['middleware' => 'json.only'], function (){
        /** Signup */
        Route::prefix('signup')->group(function () {
            Route::post('/', [AuthController::class, 'signup']);
            Route::post('/resend', [AuthController::class, 'resend'])->middleware('auth:api');
        });
        /** Signin */
        Route::post('signin', [AuthController::class, 'signin']);
        /** Signout*/
        Route::post('signout', [AuthController::class, 'signout'])->middleware('auth:api');
        /** Google Signin  */
        Route::post('signin/{provider}', [AuthController::class, 'oauth']);
        /** Midleware Auth */
        Route::group(['middleware' => ['auth:api', 'email-verify.checker']], function ()
        {
            /** User */
            Route::prefix('user')->group(function () {
                Route::get('articles', [UserController::class, 'myArticles']);
                Route::get('check', [UserController::class, 'check']);
                /** User/Password */
                Route::prefix('password')->group(function () {
                    Route::post('add', [UserController::class, 'setPassword']);
                    Route::post('change', [UserController::class, 'changePassword']);
                });
                /** User/Profile */
                Route::prefix('profile')->group(function () {
                    Route::get('/', [UserController::class, 'getProfile']);
                    Route::post('/update', [UserController::class, 'updateProfile']);
                });
            });
        });
        /** Article */
        Route::apiResource('article', ArticleController::class);
        Route::prefix('article')->group(function () {
            /** Artticle/Comment */
            Route::get('{url}/comment', [CommentController::class, 'index']);
            Route::post('{url}/comment', [CommentController::class, 'store']);
            Route::delete('{url}/comment/{id}', [CommentController::class, 'destroy']);
            /** Article/Like */
            Route::get('{url}/like', [LikeController::class, 'index']);
            Route::post('{url}/like', [LikeController::class, 'store']);
        });
    });
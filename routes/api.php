<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\UserActionLogsController;

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

Route::post('auth/login', 'Api\AuthController@login');
Route::post('users', 'Api\UserController@store');

Route::group(['middleware' => 'apiJwt'], function ($router) {
    Route::post('logout', 'Api\AuthController@logout');

    Route::get('users', 'Api\UserController@index');
    Route::get('users/:id', 'Api\UserController@show');
    Route::put('users/:id', 'Api\UserController@update');
    Route::delete('users/:id', 'Api\UserController@destroy');
    Route::apiResource('books', 'Api\BooksController');

    Route::post('logs', [UserActionLogsController::class, 'store']);
});
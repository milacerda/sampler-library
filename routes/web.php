<?php

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

// Route::get('/{any}', function ($any) {
//     return view('index');
// })->where('any', '.*');

Route::get('{slug}', function () {
    return view('index');
}); // this will ensure all routes will serve index.php file

Route::get('/', function () {
    return view('index');
});
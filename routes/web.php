<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', 'API\WebController@index');


Route::prefix('api/v1')->name('api:v1:')->middleware(['api', 'api:web'])->group(function() {
    Route::get('user/avatar', 'API\UserController@getByAvatarByUid')->name('user.avatar');
});

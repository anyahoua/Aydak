<?php

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

/*
Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::prefix('v1')->group(function(){

    // Pays :
    Route::get('payslist', 'Api\PaysController@index');
    Route::get('wilayas/{pays_id}', 'Api\WilayaController@index');


    // Users :
/*
    Route::post('login', 'Api\UserController@login');
    Route::post('register', 'Api\UserController@register');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('userdetails', 'Api\UserController@details');
    });
*/
    Route::prefix('users')->group(function(){
        
        Route::post('login', 'Api\UserController@login');
        Route::post('register', 'Api\UserController@register');
        
        Route::middleware('auth:api')->post('userdetails', 'Api\UserController@details');

    });

    // Clients:

    //Route::post('clogin', 'Api\ClientController@login');
    //Route::post('cregister', 'Api\ClientController@register');
    
    Route::prefix('clients')->group(function(){
/*
        //Route::resource('/{site}', 'EtageController');
        Route::get('/site/{site}', 'EtageController@index')->name('etages.index');
        Route::get('/site/{site}/create', 'EtageController@create')->name('etages.create');
        Route::post('/site/{site}', 'EtageController@store')->name('etages.store');
        Route::get('/{etage}/edit', 'EtageController@edit')->name('etages.edit');
        Route::put('/{etage}', 'EtageController@update')->name('etages.update');
        Route::delete('/{etage}', 'EtageController@destroy')->name('etages.destroy');
*/
        Route::post('login', 'Api\ClientController@login');
        Route::post('register', 'Api\ClientController@register');

        Route::middleware('auth:client-api')->post('clientdetails', 'Api\ClientController@details');
    });

    /*
    Route::group(['middleware' => 'auth:client-api'], function(){
        Route::post('clientdetails', 'Api\ClientController@details');
    });
    */

});
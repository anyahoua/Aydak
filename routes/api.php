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


    // Users (TEAMLEADER/SHOPPER) :
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
        
        // TEAMLEADER :
        //-------------
        Route::post('tm/nextregister', 'Api\UserController@registerNextTeamleader');
        Route::middleware('auth:api')->post('tm/invitation', 'Api\UserController@InvitationShopper');
        Route::middleware('auth:api')->get('tm/orders', 'Api\UserController@ordersDetails');



        // SHOPPER :
        //-------------
        Route::post('shopper/code', 'Api\UserController@validateCode');
        Route::post('shopper/nextregister', 'Api\UserController@registerNextShopper');
        



        //
        Route::middleware('auth:api')->post('userdetails', 'Api\UserController@details');
        Route::middleware('auth:api')->post('profile', 'Api\UserController@profile');

    });

    // Groupes
    Route::prefix('groupes')->group(function(){
        Route::get('show/{id}', 'Api\GroupeController@show');
        Route::get('findnearest', 'Api\GroupeController@findNearestGroupes');
        
    });




    // Clients:
    //-------------
    
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

        Route::middleware('auth:client-api')->get('clientdetails', 'Api\ClientController@details');
        Route::middleware('auth:client-api')->get('moncompte', 'Api\ClientController@myAccount');
        Route::middleware('auth:client-api')->get('historiquecompte', 'Api\ClientController@myAccountHistory');
        Route::middleware('auth:client-api')->get('tmcontact', 'Api\ClientController@ContactTeamleader');
        Route::middleware('auth:client-api')->get('categories', 'Api\CategorieController@index');
        Route::middleware('auth:client-api')->get('souscategories/{caterorie_id}', 'Api\CategorieController@SubCaterory');
        
        // Commandes :
        Route::middleware('auth:client-api')->get('commandesencours', 'Api\CommandeController@myCurrentOrders');
        Route::middleware('auth:client-api')->post('commande', 'Api\CommandeController@AddOrder');
    });

    /*
    Route::group(['middleware' => 'auth:client-api'], function(){
        Route::post('clientdetails', 'Api\ClientController@details');
    });
    */

});
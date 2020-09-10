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
    // Route::get('payslist', 'Api\PaysController@index');
    // Route::get('wilayas/{pays_id}', 'Api\WilayaController@index');


    // Users (TEAMLEADER/SHOPPER) :
    /*
    |-------------------------------------------------------------------------------
    | USER          : 
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/users/
    |-------------------------------------------------------------------------------
    */
    Route::prefix('users')->group(function(){
        
        Route::post('login', 'Api\UserController@login');
        Route::post('register', 'Api\UserController@register');
        Route::post('refresh', 'Api\UserController@refreshToken');

        Route::middleware('auth:api')->get('logout', 'Api\UserController@logout');
        Route::middleware('auth:api')->post('profile', 'Api\UserController@profile');
        Route::middleware('auth:api')->post('userdetails', 'Api\UserController@details');

        /* TEAMLEADER : */
        /* ------------ */
        Route::post('tm/nextregister', 'Api\UserController@registerNextTeamleader');
        Route::middleware('auth:api')->post('tm/switchlogin', 'Api\UserController@switchLogin');
        Route::middleware('auth:api')->post('tm/invitation', 'Api\UserController@InvitationShopper');

        Route::middleware('auth:api')->post('tm/paymentToShopper', 'Api\FinancialOperationController@paymentFromTeamleaderToShopper');

        // Orders :
        Route::middleware('auth:api')->get('tm/orders', 'Api\UserController@ordersDetails');
        Route::middleware('auth:api')->get('tm/ordersnottraited', 'Api\CommandeController@ordersNotTraited');
        Route::middleware('auth:api')->get('tm/orderstraitednotassigned', 'Api\CommandeController@ordersTraitedNotAssigned');
        Route::middleware('auth:api')->get('tm/ordersassignednotpurchased', 'Api\CommandeController@ordersAssignedNotPurchased');
        Route::middleware('auth:api')->get('tm/orderspurchaseduncontrolled', 'Api\CommandeController@ordersPurchasedUncontrolled');
        Route::middleware('auth:api')->get('tm/orderscontrollednotdelivered', 'Api\CommandeController@ordersControlledNotDelivered');
        Route::middleware('auth:api')->get('tm/ordersdelivered', 'Api\CommandeController@ordersDelivered');
        
        Route::middleware('auth:api')->post('tm/ordersupdatesituation', 'Api\CommandeController@ordersUpdateSituation');
        

        /* SHOPPER :    */
        /* ------------ */
        Route::post('shopper/code', 'Api\UserController@validateCode');
        Route::post('shopper/nextregister', 'Api\UserController@registerNextShopper');

        Route::middleware('auth:api')->post('shopper/purchase', 'Api\FinancialOperationController@purchase');
        Route::middleware('auth:api')->post('shopper/paymentToTeamleader', 'Api\FinancialOperationController@paymentFromShopperToTeamleader');
        
        
        
    });


    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : 
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/
    |-------------------------------------------------------------------------------
    */
    Route::prefix('clients')->group(function(){

        // Register, Login & Logout
        Route::post('register', 'Api\ClientController@register');
        Route::post('login', 'Api\ClientController@login');
        Route::middleware('auth:client-api')->get('logout', 'Api\ClientController@logout');
        
        // Groupes
        Route::get('findnearest', 'Api\GroupeController@findNearestGroupes');

        // Client
        Route::middleware('auth:client-api')->get('myDetails', 'Api\ClientController@details');

        // Groupes, Teamleader
        Route::middleware('auth:client-api')->get('groupesList', 'Api\GroupeController@groupeListe');
        Route::middleware('auth:client-api')->get('myTeamleader', 'Api\ClientController@ContactTeamleader');

        // Categories, Subcategories ...
        Route::middleware('auth:client-api')->get('categories', 'Api\CategorieController@index');
        Route::middleware('auth:client-api')->get('subcategories/{caterorie_id}', 'Api\CategorieController@SubCaterory');

        // Products
        Route::middleware('auth:client-api')->get('productsList', 'Api\ProductController@productListe');
        Route::middleware('auth:client-api')->get('productFavoritsListe', 'Api\ProductController@productFavoritsListe');
        Route::middleware('auth:client-api')->post('addProductFavorit', 'Api\ProductController@addProductFavorit');
        Route::middleware('auth:client-api')->delete('deleteProductFavorit/{favorit}', 'Api\ProductController@deleteProductFavorit');
        Route::middleware('auth:client-api')->get('searchProduct', 'Api\ProductController@searchProduct');

        // Wallet
        Route::middleware('auth:client-api')->get('myAccount', 'Api\FinancialOperationController@myAccount');
        Route::middleware('auth:client-api')->get('accountHistory', 'Api\FinancialOperationController@myAccountHistory');
        Route::middleware('auth:client-api')->get('balance', 'Api\FinancialOperationController@mySolde');
        Route::middleware('auth:client-api')->put('validatePayment', 'Api\FinancialOperationController@validateVersement');
        Route::middleware('auth:client-api')->post('paymentToTeamleader', 'Api\FinancialOperationController@paymentFromClientToTeamleader');
        
        // Orders :
        Route::middleware('auth:client-api')->get('currentOrders', 'Api\ClientController@myCurrentOrders');
        Route::middleware('auth:client-api')->post('order', 'Api\CommandeController@addOrder');
        Route::middleware('auth:client-api')->put('cancelOrder/{commande}', 'Api\CommandeController@cancelOrder');
        Route::middleware('auth:client-api')->post('addCommentCommande', 'Api\CommandeController@addCommentCommande');
        Route::middleware('auth:client-api')->put('validateReceiptOrder/{commande}', 'Api\CommandeController@validateReceiptOrder');
    });


    // Groupes
    Route::prefix('groupes')->group(function(){
        Route::get('show/{id}', 'Api\GroupeController@show');
        
    });

});
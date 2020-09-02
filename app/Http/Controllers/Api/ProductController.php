<?php

namespace App\Http\Controllers\Api;

use App\Models\Produit;
use App\Models\ProduitPrix;
use App\Models\ClientPreferenceAchat;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;


use App\Http\Resources\Api\Produits\ProduitsListeRessource;
use App\Http\Resources\Api\Produits\ProduitsListeRessourceCollection;
use App\Http\Resources\Api\Produits\ProduitsFavoritsListeRessource;
use App\Http\Resources\Api\Produits\ProduitsFavoritsListeRessourceCollection;

use Validator;

class ProductController extends ApiController
{
    /** 
     * Show All Products API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request) 
    {
        $produits = Produit::where('etat', '1')->get();

        return $this->successResponse($produits, 'Successfully');
        //return $this->successResponse(ProductsIndexRessource::collection($produits), 'Successfully');
    }

 
    /*
    |-------------------------------------------------------------------------------
    | Products List
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/clients/productsList
    | Method:         GET
    | Description:    Show All Products in the List.
    */
    public function productListe(Request $request)
    {
        $Products       = Produit::where('etat', 1)->paginate(5);
        
        //->withCount('shoppersInGroupe')
        //->with(['TeamleaderInGroupe', 'shoppersInGroupe'])
        //->with(['uniteMesure', 'famille', 'familleSousCategorie'])
        
        return new ProduitsListeRessourceCollection($Products);
        //return $this->successResponse(new ProduitsListeRessourceCollection($Products), 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | List of favored products
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/clients/productFavoritsListe
    | Method:         GET
    | Description:    Show All Favorits Products By Client in the List.
    */
    public function productFavoritsListe(Request $request)
    {
        $client             = Auth::user();
        $ProductsFavoris    = $client->clientPreferenceAchat()->paginate(3);

        return new ProduitsFavoritsListeRessourceCollection($ProductsFavoris);
    }

    /*
    |-------------------------------------------------------------------------------
    | Add favored product
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/clients/addProductFavorit
    | Method:         POST
    | Description:    Add Favorit Product By Client.
    */
    public function addProductFavorit(Request $request)
    {
        $client                 = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'productId' => 'required|integer|unique:client_preference_achats,produit_id,NULL,id,client_id,'.$client->id.'',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        $Favoris                = new ClientPreferenceAchat;

        $Favoris->client_id     = $client->id;
        $Favoris->produit_id    = $request->productId;

        $Favoris->save();


        //return new ProduitsFavoritsListeRessource($Favoris);
        return $this->successResponse(new ProduitsFavoritsListeRessource($Favoris), 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | Delete favored product
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/clients/deleteProductFavorit/{favorit_id}
    | Method:         POST
    | Description:    Delete Favorit Product By Client.
    */
    public function deleteProductFavorit(Request $request, ClientPreferenceAchat $favorit)
    {
        $client             = Auth::user();
        //$FavoritProduct = ClientPreferenceAchat::where('client_id', $client->id)->where('produit_id', $request->productId)->first();

        if($favorit->client_id !== $client->id)
        {
            return $this->errorResponse('Forbidden', 403);
        }

        $favorit->delete();

        return $this->successResponse($favorit, 'Successfully');
        
    }


}

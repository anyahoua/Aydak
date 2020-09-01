<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Models\Categorie;
use App\Models\SousCategorie;
/*
use App\Models\ClientInfo;
use App\Models\ClientCompte;
use App\Models\ClientPreferenceAchat;
use App\Models\Commande;
use App\Models\CommandeDetail;
use App\Models\CommandeCommentaire;
*/

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\Api\Categories\CategoriesIndexRessource;
use App\Http\Resources\Api\Categories\SousCategoriesRessource;

use Validator;

class CategorieController extends ApiController
{
    
    /** 
     * Show All Categories API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request) 
    {
        $categories = Categorie::where('etat', '1')->get();

        //return $this->successResponse($categories, 'Successfully');
        return $this->successResponse(CategoriesIndexRessource::collection($categories), 'Successfully');
    }


    /** 
     * Show All Sous Categories API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function SubCaterory(Request $request, $caterorie_id) 
    {
        $sous_categories = SousCategorie::where('categorie_id', $caterorie_id)->where('etat', '1')->get();

        return $this->successResponse(SousCategoriesRessource::collection($sous_categories), 'Successfully');
    }



}

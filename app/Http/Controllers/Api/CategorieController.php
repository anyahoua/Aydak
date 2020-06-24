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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use App\Traits\UploadTrait;

use Validator;
//use Keygen;

class CategorieController extends Controller
{
    
    /** 
     * Show All Categories API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index(Request $request) 
    {
        $categories = Categorie::where('etat', '1')->get();

        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => $categories
        ], 200);
    }


    /** 
     * Show All Sous Categories API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function SubCaterory(Request $request, $caterorie_id) 
    {
        //return 'Category : '.$caterorie_id;
        
        $sous_categories = SousCategorie::where('categorie_id', $caterorie_id)->where('etat', '1')->get();

        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => $sous_categories
        ], 200);
        
    }



}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Pays;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class PaysController extends Controller
{
    
    /** 
     * Pays api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index() 
    { 
        $pays = Pays::all(); 
        
        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => $pays
        ], 200);
    }



}

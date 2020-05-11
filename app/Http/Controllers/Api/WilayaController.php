<?php

namespace App\Http\Controllers\Api;

use App\Models\Wilaya;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class WilayaController extends Controller
{
    /** 
     * Wilaya api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function index($pays_id)
    { 
        $wilaya = Wilaya::where('pays_id', $pays_id)->get(); 
        
        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => $wilaya
        ], 200);
    }





}

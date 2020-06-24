<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Models\Groupe;
use App\Models\Commande;
use App\Models\CommandeDetail;
use App\Models\CommandeCommentaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Traits\UploadTrait;

use Validator;
use Keygen;

class CommandeController extends Controller
{



    /** 
     * Connected Client Show Current Orders API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function myCurrentOrders() 
    { 
        $orders = Auth::user(); 
        $CurrentOrders = $orders->commandesEnCours;
        

        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => $CurrentOrders
        ], 200);
    }


    /** 
     * Client Add Order API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function AddOrder(Request $request) 
    { 
        $validator = $request->validate([
            'expectedDeliveryDate'  => 'required | date_format:d-m-Y',
            //'quantity'              => 'required | integer',
            //'unitPrice'             => 'required | regex:/^\d+(\.\d{1,2})?$/',
            //'productId'             => 'required | integer',
        ]/*,
        [
            'doctorId.required'     => 'Le champ id medecin est obligatoire.',
            'doctorId.required'     => 'Le champ id medecin doite être un entier.',
            'dossierId.required'    => 'Le champ id dossier est obligatoire.',
            'dossierId.integer'     => 'Le champ id dossier doite être un entier.',
            'typeId.required'       => 'Le champ id type est obligatoire.',
            'typeId.integer'        => 'Le chape id type doite être un entier.',
        ]*/);

        

        // This Client :
        //------------------
        $client = Auth::user(); 

        //return $client;

        // Commande :
        //------------------
        $data = [
            'date_livraison_prevu'  => Carbon::createFromFormat('d-m-Y', $request->expectedDeliveryDate)->format('Y-m-d'),
            'situation_id'          => '1',
            'client_id'             => $client->id,
            'groupe_id'             => $client->groupe_id,
        ];

        $commande   = Commande::create($data);


        //------------------------------//
        // Add Detail commande  //
        //------------------------------//
        $entray = json_decode($request->getContent(), true);


        $detailCommande = array();
        foreach($entray["order"] as $key => $row)
        {
            $detailCommande[] = array(
                'quantite_commande' => $row['quantity'],
                'prix_u_commande'   => $row['unitPrice'],
                'etat'              => '0',
                'commande_id'       => $commande->id,
                'produit_id'        => $row['productId'],
            );
        }

        //return $data2;
        CommandeDetail::insert($detailCommande);

        $commande->situation;

        return response()->json([
            'code'      => '201',
            'message'   => 'Commande envoyé avec success.',
            'data'      => $commande
        ], 201);
        
    }


}

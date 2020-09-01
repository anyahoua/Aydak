<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Models\Groupe;
use App\Models\Commande;
use App\Models\CommandeDetail;
use App\Models\CommandeCommentaire;
use App\Models\UserCommande;
use App\Models\ProduitPrix;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Traits\UploadTrait;

use App\Http\Resources\Api\Users\OrdersRessource;
use App\Http\Resources\Api\Users\UsersOrdersRessource;

use Validator;
use Keygen;

//class CommandeController extends Controller
class CommandeController extends ApiController
{

    /** 
     * Connected User Teamleader Show Orders Not Traited API 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function ordersNotTraited() 
    { 
        $user = Auth::user();

        $Orders = Commande::where('groupe_id', $user->groupe->id)
                    ->where('situation_id', '1')
                    ->get();
        
        return $this->successResponse(OrdersRessource::collection($Orders), 'Successfully');
/*
        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => OrdersRessource::collection($Orders),
            //'data'      => $this->Orders,
        ], 200);
*/
    }
    
    /** 
     * Connected User Teamleader Show Orders Traited Not Assigned API 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function ordersTraitedNotAssigned() 
    { 
        $user = Auth::user();

        $Orders = Commande::where('groupe_id', $user->groupe->id)
                    ->where('situation_id', '3')
                    ->get();
        
        return $this->successResponse(OrdersRessource::collection($Orders), 'Successfully');
/*
        return response()->json([
            'code'      => '200',
            'message'   => 'Success.',
            'data'      => OrdersRessource::collection($Orders),
            //'data'      => $this->Orders,
        ], 200);
*/
    }


    /** 
     * Connected User Teamleader Show Orders Assigned Not Purchased API 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function ordersAssignedNotPurchased() 
    { 
        $user = Auth::user();
        //return $user->groupe->shoppersInGroupe;
        return $this->successResponse(UsersOrdersRessource::collection($user->groupe->shoppersInGroupe), 'Successfully');
    }


    /** 
     * Client Add Order API 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function AddOrder(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'expectedDeliveryDate'  => 'required | date_format:d-m-Y',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }


        //----------------------//
        // This Client :
        //----------------------//
        $client = Auth::user(); 

        //----------------------//
        // Add Commande :
        //----------------------//
        $data = [
            'date_livraison_prevu'  => Carbon::createFromFormat('d-m-Y', $request->expectedDeliveryDate)->format('Y-m-d'),
            'situation_id'          => '1',
            'client_id'             => $client->id,
            'groupe_id'             => $client->groupe_id,
        ];

        $commande   = Commande::create($data);

        //----------------------//
        // Add Detail commande :
        //----------------------//
        $entray = json_decode($request->getContent(), true);


        $detailCommande = array();
        foreach($entray["order"] as $key => $row)
        {

            //Prix produit
            $prix_unitaire = ProduitPrix::where('produit_id', $row['productId'])->first();

            $detailCommande[] = array(
                'quantite_commande' => $row['quantity'],
                'prix_u_commande'   => $prix_unitaire->prix, //$row['unitPrice'],
                'etat'              => '0',
                'commande_id'       => $commande->id,
                'produit_id'        => $row['productId'],
            );
        }

        CommandeDetail::insert($detailCommande);

        $commande->situation;

        return $this->successResponse($commande, 'Commande envoyé avec success.', 201);
    }


}

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

    /*
    |-------------------------------------------------------------------------------
    | USERS         : Orders Not Traited
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/users/tm/ordersnottraited
    | Method        : GET
    | Description   : Show orders not traited by current connected teamleader.
    |-------------------------------------------------------------------------------
    | @expectedDeliveryDate : date->format(Y-m-d)
    | @productId            : int
    | @quantity             : int
    |-------------------------------------------------------------------------------
    */
    public function ordersNotTraited() 
    { 
        $user = Auth::user();

        $Orders = Commande::where('groupe_id', $user->groupe->id)
                    ->where('situation_id', '1')
        //            ->with('detailCommande')
                    ->get();
        //return $Orders;
        return $this->successResponse(OrdersRessource::collection($Orders), 'Successfully');
    }
    
    /*
    |-------------------------------------------------------------------------------
    | USERS         : Orders Traited Not Assigned
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/users/tm/orderstraitednotassigned
    | Method        : GET
    | Description   : Show orders traited not assigned by current connected teamleader.
    |-------------------------------------------------------------------------------
    */
    public function ordersTraitedNotAssigned() 
    { 
        $user = Auth::user();

        $Orders = Commande::where('groupe_id', $user->groupe->id)
                    ->where('situation_id', '3')
                    ->get();
        
        return $this->successResponse(OrdersRessource::collection($Orders), 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | USERS         : Orders Assigned Not Purchased
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/users/tm/ordersassignednotpurchased
    | Method        : GET
    | Description   : Show orders assigned not purchased by current connected teamleader.
    |-------------------------------------------------------------------------------
    */
    public function ordersAssignedNotPurchased() 
    { 
        $user = Auth::user();
        //return $user->groupe->shoppersInGroupe;
        return $this->successResponse(UsersOrdersRessource::collection($user->groupe->shoppersInGroupe), 'Successfully');
    }


    /** Orders Purchased Uncontrolled */
    public function ordersPurchasedUncontrolled() 
    { 
        $user = Auth::user();

        $Orders = Commande::where('groupe_id', $user->groupe->id)
                    ->where('situation_id', '5')
                    ->get();
        
        return $this->successResponse(OrdersRessource::collection($Orders), 'Successfully');
    }

    /** Orders Controlled Not Delivered */
    public function ordersControlledNotDelivered() 
    { 
        $user = Auth::user();

        $Orders = Commande::where('groupe_id', $user->groupe->id)
                    ->where('situation_id', '6')
                    ->get();
        
        return $this->successResponse(OrdersRessource::collection($Orders), 'Successfully');
    }

    /** Orders Delivered */
    public function ordersDelivered() 
    { 
        $user = Auth::user();

        $Orders = Commande::where('groupe_id', $user->groupe->id)
                    ->where('situation_id', '7')
                    ->get();
        
        return $this->successResponse(OrdersRessource::collection($Orders), 'Successfully');
    }

    /** Orders Situation Manipulation */
    public function ordersUpdateSituation(Request $request) 
    { 

        $user = Auth::user();

        $entray = json_decode($request->getContent(), true);


        $detailCommande = array();
        foreach($entray["orders"] as $key => $row)
        {
            $commande   = Commande::find($row['id']);

            if($user->groupe->id !== $commande->groupe_id)
            {
                return $this->errorResponse('Forbidden : ', 403);
            }

        }

        foreach($entray["orders"] as $key => $row)
        {
            $commande   = Commande::find($row['id']);

            if($user->groupe->id !== $commande->groupe_id)
            {
                return $this->errorResponse('Forbidden', 403);
            }

            $commande->update(['situation_id' => $row['situationId']]);
            
        }

return 'ok';



    }
    
    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Cancel Order
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/order
    | Method        : POST
    | Description   : Add new order by connected client.
    |-------------------------------------------------------------------------------
    | @expectedDeliveryDate : date->format(Y-m-d)
    | @productId            : int
    | @quantity             : int
    |-------------------------------------------------------------------------------
    */
    public function addOrder(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'expectedDeliveryDate'  => 'required | date_format:d-m-Y',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

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

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Cancel Order
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/cancelOrder/{commande_id}
    | Method        : POST
    | Description   : Cancel order By Client.
    |-------------------------------------------------------------------------------
    |
    | @commande_id  : int
    |
    |-------------------------------------------------------------------------------
    */
    public function cancelOrder(Request $request, Commande $commande) 
    {
        $client = Auth::user();

        if($commande->client_id !== $client->id)
        {
            return $this->errorResponse('Forbidden', 403);
        }

        $commande->update(['situation_id' => 7]);

        return $this->successResponse($commande, 'Commande annulé avec success.', 200);
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Cancel Order
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/cancelOrder/{commande_id}
    | Method        : POST
    | Description   : Cancel order By Client.
    |-------------------------------------------------------------------------------
    |
    | @commande_id  : int
    |
    |-------------------------------------------------------------------------------
    */
    public function addCommentCommande(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'comment'       => 'required | string',
            'commandeId'    => 'required | integer',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        $client     = Auth::user();
        $commande   = Commande::findOrFail($request->commandeId);

        if($commande->client_id !== $client->id)
        {
            return $this->errorResponse('Forbidden', 403);
        }

        //----------------------//
        // Add Comment Commande :
        //----------------------//
        $data = [
            'commentaire'   => $request->comment,
            'etat'          => 1,
            'user_id'       => $client->id,
            'groupe_id'     => $client->groupe->id,
            'commande_id'   => $commande->id,
            'profil_id'     => 3,
        ];

        $comment   = CommandeCommentaire::create($data);

        $comment->profil;
        $comment->groupe;
        $comment->commande;

        return $this->successResponse($comment, 'Commentaire ajouté avec success.', 201);
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Vlidate Receipt Order
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/validateReceiptOrder/{commande_id}
    | Method        : PUT
    | Description   : Cancel order By Client.
    |-------------------------------------------------------------------------------
    |
    | @commande_id  : int
    |
    |-------------------------------------------------------------------------------
    */
    public function validateReceiptOrder(Request $request, Commande $commande) 
    {
        $validator = Validator::make($request->all(), [
            'montant'   => 'required | string',
            'solde'     => 'required | string',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
        }

        $client = Auth::user();

        if($commande->client_id !== $client->id)
        {
            return $this->errorResponse('Forbidden', 403);
        }

        $commande->update(['situation_id' => 6]);

        $newSolde = $request->solde - $request->montant;

        $client->clientWallet->update(['etat' => 1, 'ancien_solde' => $request->solde, 'nouveau_solde' => $newSolde]);

        return $this->successResponse($commande, 'Commande annulé avec success.', 200);
    }
    

    
}

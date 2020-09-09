<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\User;
use App\Models\ClientCompte;
use App\Models\UserCompte;
use App\Models\Commande;
use App\Models\Commission;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Traits\ApiWallet;

use App\Http\Resources\Api\Clients\ClientCompteRessource;
use App\Http\Resources\Api\Clients\ClientNewBalanceRessource;

use Validator;

class FinancialOperationController extends ApiController
{
 
    use ApiWallet;

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Client Account
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/myAccount
    | Method        : GET
    | Description   : Show wallet by current connected Client.
    |-------------------------------------------------------------------------------
    */
    public function myAccount() 
    { 
        $client = Auth::user(); 

        return $this->successResponse(new ClientCompteRessource($client->clientCompte), 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Client Account History
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/accountHistory
    | Method        : GET
    | Description   : Show account History by current connected Client.
    |-------------------------------------------------------------------------------
    */
    public function myAccountHistory() 
    { 
        $client = Auth::user(); 

        return $this->successResponse($client->clientCompteHistory, 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : New Solde
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/balance
    | Method        : GET
    | Description   : Select new solde by current connected Client.
    |-------------------------------------------------------------------------------
    */
    public function mySolde() 
    { 
        $client = Auth::user();

        return $this->successResponse(new ClientNewBalanceRessource($client->clientNouveauSolde), 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Validate Payment
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/validatePayment
    | Method        : PUT
    | Description   : Validate payment by current connected Client.
    |-------------------------------------------------------------------------------
    | @WalletId     : int 
    | @newSolde     : Double 
    | @oldSolde     : Double 
    |-------------------------------------------------------------------------------|
    */
    public function validateVersement(Request $request) 
    { 
        $client = Auth::user();

        $client->lastClientWallet->update(['etat' => '0']);

        $wallet = ClientCompte::where('id', $request->WalletId)->update(['etat' => '1', 'nouveau_solde' => $request->newSolde, 'ancien_solde' => $request->oldSolde]);
        

        //return $this->successResponse(new ClientNewBalanceRessource($client->clientNouveauSolde), 'Successfully');
        return $this->successResponse($wallet, 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | CLIENT        : Payment to the teamleader
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/clients/paymentToTeamleader
    | Method        : POST
    | Description   : Payment to the teamleader by current connected Client.
    |-------------------------------------------------------------------------------
    |
    | @amountPaid   : Double 
    |
    |-------------------------------------------------------------------------------|
    */
    public function paymentFromClientToTeamleader(Request $request) 
    {
        /** CLIENT */
        $client                 = Auth::user();

        $oldClientWallet        = $client->lastClientWallet;
        
        $oldClientWallet->update(['etat' => '0']);

        //---
        $data['debit']          = 0;
        $data['credit']         = $request->amountPaid;
        $data['ancien_solde']   = $oldClientWallet->nouveau_solde;
        $data['nouveau_solde']  = $oldClientWallet->nouveau_solde + $request->amountPaid;
        $data['etat']           = 1;
        $data['client_id']      = $client->id;
        $data['groupe_id']      = $client->groupe_id;
        $data['type']           = 'versement';
        $data['commande_id']    = 0;

        $newClientWallet        = $this->AddClientWallet($data);
        


        /** TEAMLEADER */
        $teamleader             = $client->groupe->TeamleaderInGroupe;
        
        $oldTeamleaderWallet    = $teamleader->TeamleaderWallet;

        $oldTeamleaderWallet->update(['etat' => '0']);

        $data['debit']          = 0;
        $data['credit']         = $request->amountPaid;
        $data['ancien_solde']   = $oldTeamleaderWallet->nouveau_solde;
        $data['nouveau_solde']  = $oldTeamleaderWallet->nouveau_solde + $request->amountPaid;
        $data['etat']           = 1;
        $data['user_id']        = $teamleader->id;
        $data['profil_id']      = 1;
        $data['groupe_id']      = $teamleader->groupe->id;
        $data['type']           = 'versement';
        $data['commande_id']    = 0;

        $newTeamleaderWallet    = $this->AddUserWallet($data);

        return $this->successResponse($newTeamleaderWallet, 'Successfully');
    }

    /*
    |-------------------------------------------------------------------------------
    | TEAMLEADER    : Payment from teamleader to the shopper
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/users/tm/paymentToShopper
    | Method        : POST
    | Description   : Payment to the shopper by current connected teamleader.
    |-------------------------------------------------------------------------------
    |
    | @amountPaid   : Double 
    | @shopperId    : int
    |
    |-------------------------------------------------------------------------------|
    */
    public function paymentFromTeamleaderToShopper(Request $request) 
    {
        /** TEAMLEADER */
        $teamleader             = Auth::user();
        
        $oldTeamleaderWallet    = $teamleader->userWallet;

        $oldTeamleaderWallet->update(['etat' => '0']);

        $data['debit']          = $request->amountPaid;
        $data['credit']         = 0;
        $data['ancien_solde']   = $oldTeamleaderWallet->nouveau_solde;
        $data['nouveau_solde']  = $oldTeamleaderWallet->nouveau_solde - $request->amountPaid;
        $data['etat']           = 1;
        $data['user_id']        = $teamleader->id;
        $data['profil_id']      = 1;
        $data['groupe_id']      = $teamleader->groupe->id;
        $data['type']           = 'versement';
        $data['commande_id']    = 0;

        $newTeamleaderWallet    = $this->AddUserWallet($data);
        //---


        /** SHOPPER */
        $shopper             = User::findOrFail($request->shopperId);
        
        $oldShopperWallet    = $shopper->ShopperWallet;

        $oldShopperWallet->update(['etat' => '0']);

        $data['debit']          = 0;
        $data['credit']         = $request->amountPaid;
        $data['ancien_solde']   = $oldShopperWallet->nouveau_solde;
        $data['nouveau_solde']  = $oldShopperWallet->nouveau_solde + $request->amountPaid;
        $data['etat']           = 1;
        $data['user_id']        = $shopper->id;
        $data['profil_id']      = 2;
        $data['groupe_id']      = $shopper->groupe->id;
        $data['type']           = 'versement';
        $data['commande_id']    = 0;

        $newShopperWallet    = $this->AddUserWallet($data);

    }

    /*
    |-------------------------------------------------------------------------------
    | SHOPPER       : Purchase by shopper
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/users/shopper/purchase
    | Method        : POST
    | Description   : Purchase by current connected shopper.
    |-------------------------------------------------------------------------------
    |
    | @amountPaid   : Double 
    | @orderId      : int
    |
    |-------------------------------------------------------------------------------|
    */
    public function purchase(Request $request) 
    {
        //$rate = Commission::where

        /** SHOPPER */
        $shopper                = Auth::user();

        $commissionAydak        = Commission::find(1);

        $oldShopperWallet       = $shopper->ShopperWallet;
 
        //--

        $trueAmount = $oldShopperWallet->nouveau_solde - ($commissionAydak->valeur/100)*$oldShopperWallet->nouveau_solde;
        //return $trueAmount;

        
        if($request->amountPaid > $trueAmount)
        {
            return $this->errorResponse('Your balance does not allow this transaction : '.$trueAmount, 401);
        }
        //--


        $oldSolde               = $oldShopperWallet->nouveau_solde;
        $newSolde               = $oldShopperWallet->nouveau_solde - $request->amountPaid;

return 'new balance : '.$newSolde;


        $oldShopperWallet->update(['etat' => '0']);

        $data['debit']          = $request->amountPaid;
        $data['credit']         = 0;
        $data['ancien_solde']   = $oldSolde;
        $data['nouveau_solde']  = $newSolde;
        $data['etat']           = 1;
        $data['user_id']        = $shopper->id;
        $data['profil_id']      = 2;
        $data['groupe_id']      = $shopper->groupe->id;
        $data['type']           = 'achat';
        $data['commande_id']    = $request->orderId;

        $newShopperWallet       = $this->AddUserWallet($data);


        /** CLIENT */
        $order                  = Commande::findOrFail($request->orderId);
        $client                 = $order->client;

        $oldClientWallet        = $client->lastClientWallet;
        
        $oldClientWallet->update(['etat' => '0']);

        //---
        $data['debit']          = $request->amountPaid;
        $data['credit']         = 0;
        $data['ancien_solde']   = $oldClientWallet->nouveau_solde;
        $data['nouveau_solde']  = $oldClientWallet->nouveau_solde - $request->amountPaid;
        $data['etat']           = 1;
        $data['client_id']      = $client->id;
        $data['groupe_id']      = $client->groupe->id;
        $data['type']           = 'achat';
        $data['commande_id']    = $order->id;

        $newClientWallet        = $this->AddClientWallet($data);
        

        return $newClientWallet;
    }

    /*
    |-------------------------------------------------------------------------------
    | TEAMLEADER    : Payment from shopper to the teamleader
    |-------------------------------------------------------------------------------
    | URL           : /api/v1/users/shopper/paymentToTeamleader
    | Method        : POST
    | Description   : Payment to the teamleader by current connected shopper.
    |-------------------------------------------------------------------------------
    |
    | @amountPaid   : Double 
    |
    |-------------------------------------------------------------------------------|
    */
    public function paymentFromShopperToTeamleader(Request $request) 
    {
        /** SHOPPER */
        $shopper                = Auth::user();
        
        $oldShopperWallet       = $shopper->ShopperWallet;

        $oldSolde               = $oldShopperWallet->nouveau_solde;
        $newSolde               = $oldShopperWallet->nouveau_solde - $request->amountPaid;

        if($newSolde < 0)
        {
            return $this->errorResponse('Unauthorised. Bad new solde', 401);
        }

        $oldShopperWallet->update(['etat' => '0']);

        $data['debit']          = $request->amountPaid;
        $data['credit']         = 0;
        $data['ancien_solde']   = $oldSolde;
        $data['nouveau_solde']  = $newSolde;
        $data['etat']           = 1;
        $data['user_id']        = $shopper->id;
        $data['profil_id']      = 2;
        $data['groupe_id']      = $shopper->groupe->id;
        $data['type']           = 'versement';
        $data['commande_id']    = 0;

        $newShopperWallet       = $this->AddUserWallet($data);


        /** TEAMLEADER */
        $teamleader             = $shopper->groupe->TeamleaderInGroupe;
        
        $oldTeamleaderWallet    = $teamleader->userWallet;

        $oldTeamleaderWallet->update(['etat' => '0']);

        $data['debit']          = 0;
        $data['credit']         = $request->amountPaid;
        $data['ancien_solde']   = $oldTeamleaderWallet->nouveau_solde;
        $data['nouveau_solde']  = $oldTeamleaderWallet->nouveau_solde + $request->amountPaid;
        $data['etat']           = 1;
        $data['user_id']        = $teamleader->id;
        $data['profil_id']      = 1;
        $data['groupe_id']      = $teamleader->groupe->id;
        $data['type']           = 'versement';
        $data['commande_id']    = 0;

        $newTeamleaderWallet    = $this->AddUserWallet($data);
        //---

        return $newTeamleaderWallet;
    }



}

<?php

namespace App\Traits;

use App\Models\ClientCompte;
use App\Models\UserCompte;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;



trait ApiWallet
{

    public function AddClientWallet($data=null)
    {
        
        $Wallet                 = new ClientCompte;

        $Wallet->debit          = $data['debit'];
        $Wallet->credit         = $data['credit'];
        $Wallet->ancien_solde   = $data['ancien_solde'];
        $Wallet->nouveau_solde  = $data['nouveau_solde'];
        $Wallet->etat           = $data['etat'];
        $Wallet->client_id      = $data['client_id'];
        $Wallet->groupe_id      = $data['groupe_id'];
        $Wallet->type           = $data['type'];
        $Wallet->commande_id    = $data['commande_id'];

        $Wallet->save();
        
        
        return $Wallet;
    }


    public function AddUserWallet($data=null)
    {
        
        $Wallet                 = new UserCompte;

        $Wallet->debit          = $data['debit'];
        $Wallet->credit         = $data['credit'];
        $Wallet->ancien_solde   = $data['ancien_solde'];
        $Wallet->nouveau_solde  = $data['nouveau_solde'];
        $Wallet->etat           = $data['etat'];
        $Wallet->user_id        = $data['user_id'];
        $Wallet->profil_id      = $data['profil_id'];
        $Wallet->groupe_id      = $data['groupe_id'];
        $Wallet->type           = $data['type'];
        $Wallet->commande_id    = $data['commande_id'];

        $Wallet->save();
        
        
        return $Wallet;
    }




}
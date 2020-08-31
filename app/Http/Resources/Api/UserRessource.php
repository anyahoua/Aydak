<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            
            //'1' => $this->ordersUser1->count(),
            //'userCommandes1' => $this->userStateCommandesTeamleader(),
            //'userCommandes' => OrderStateRessource::collection($this->userCommandes_2),
            //'ORDERS'        => OrderStateRessource::collection($this->ordersUser),

            'user'=> [
                
                'userId'            => $this->id,
                'groupeId'          => $this->groupe->id,
                'groupeName'        => $this->groupe->nom,
                'userType'          => $this->userInfo->profil_id,
                'isLeader'          => $this->userInfo->teamleader_shopper,
                'type'              => $this->userInfo->profil->nom,
                'lastName'          => $this->nom,
                'firstName'         => $this->prenom,
                'mobile'            => $this->username,
                'avatar'            => $this->userInfo->avatar,
                'actived'           => $this->userInfo->etat,
                'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
                'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
                //---------------------------
                'rating'            => 0,    // 4.5, 2, 3.6 ----> x/5
                'totalReviews'      => 0,    // 2, 8, 10 votes (nbr de votes)
                //---------------------------
                'billingAddress'    => $this->userInfo->adresse_residence,
                'locationAddress'   => new locationAddressRessource($this->userLocationAddress),
                
            ],
            //'wallet'        => $this->userCompte ? new UserCompteRessource($this->userCompte) : [],
            //'wallet'        => $this->userCompte ? new UserCompteRessource($this->userCompte) : new \ArrayObject(),
            'wallet'        => $this->userWallet ? new UserCompteRessource($this->userWallet) : new \ArrayObject(),
            'orders'        => OrdersRessource::collection($this->ordersUser),

            //---------------------------
            'ordersState'   => ['totalOrders' => $this->ordersUser->count(), 'orderState' => $this->userStateCommandesShoppers() ],
            
            // For teamleader :
            //-----------------
            //'ordersState'   => ['totalOrders' => $this->totalOrdersTm(), 'orderState' => $this->userStateCommandesTeamleader() ],
            //---------------------------
        ];
    }
}
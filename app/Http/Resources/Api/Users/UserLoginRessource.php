<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserLoginRessource extends JsonResource
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

            'userId'            => $this->id,
            'groupeId'          => $this->groupe->id,
            'groupeName'        => $this->groupe->nom,
            'userType'          => $this->userInfo->profil_id,
            
            'currentUserType'   => $this->currentProfilId, /* ? $this->currentProfilId : '22',*/
            //'CurrentuserType'   => $this->userInfo->current_profil_id, /* ? $this->currentProfilId : '22',*/

            'isLeader'          => $this->userInfo->teamleader_shopper,
            'type'              => $this->userInfo->profil->nom,
            'lastName'          => $this->nom,
            'firstName'         => $this->prenom,
            'mobile'            => $this->username,
            'avatar'            => $this->userInfo->avatar,
            'actived'           => $this->userInfo->etat,
            'token'             => $this->access_token,
            'tokenExpireAt'     => $this->expires_in,
            'refreshToken'      => $this->refresh_token,
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
            //---------------------------
            //'rating'            => '0',    // 4.5, 2, 3.6 ----> x/5
            //'totalReviews'      => 0,    // 2, 8, 10 votes (nbr de votes)

            'rating'            => $this->avgUserVote() ? number_format($this->avgUserVote(), 2, '.', '') : '0',
            'totalReviews'      => $this->totalUserVote() ? $this->totalUserVote() : 0,
            //'testRatingData' => $this->userVote,

            //---------------------------
            'billingAddress'    => $this->userInfo->adresse_residence,
            'locationAddress'   => new locationAddressRessource($this->userLocationAddress),
            //'wallet'            => new UserCompteRessource($this->userCompte),
            'wallet'            => new UserCompteRessource($this->userWallet),

            //'BiometricFile'     => UserDocumentRessource::collection($this->documents),
            
            //'ordersState'   => ['totalOrders' => $this->totalOrdersTm(), 'orderState' => $this->userStateCommandesTeamleader() ],
            'ordersState'       => $this->orderStateUserLogin(),
        ];
    }
}

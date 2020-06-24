<?php

namespace App\Http\Resources\Api;

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
            'groupeId'          => $this->groupeUser->id,
            'userType'          => $this->userInfo->profil_id,
            'type'              => $this->userInfo->profil->nom,
            'lastName'          => $this->nom,
            'firstName'         => $this->prenom,
            'mobile'            => $this->username,
            'avatar'            => null,
            'actived'           => $this->userInfo->etat,
            'token'             => $this->apitoken,
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
            'billingAddress'    => $this->userInfo->adresse_residence,
            'locationAddress'   => new locationAddressRessource($this->userLocationAddress),
            //'pays'              => $this->userLocationAddress->pays->nom,
            
            'wallet'            => new UserCompteRessource($this->userCompte),

        ];
    }
}

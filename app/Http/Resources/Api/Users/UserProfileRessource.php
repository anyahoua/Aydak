<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserProfileRessource extends JsonResource
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
            'isLeader'          => $this->userInfo->teamleader_shopper,
            'type'              => $this->userInfo->profil->nom,
            'lastName'          => $this->nom,
            'firstName'         => $this->prenom,
            'mobile'            => $this->username,
            'avatar'            => $this->userInfo->avatar,
            'actived'           => $this->userInfo->etat,
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
            'billingAddress'    => $this->userInfo->adresse_residence,
            'locationAddress'   => new locationAddressRessource($this->userLocationAddress),
        
        ];
    }
}
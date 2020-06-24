<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ClientLoginRessource extends JsonResource
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
            'clientId'          => $this->id,
            'groupeId'          => $this->groupe_id,
            'lastName'          => $this->nom,
            'firstName'         => $this->prenom,
            'mobile'            => $this->username,
            'avatar'            => null,
            'actived'           => $this->clientInfo->etat,
            'token'             => $this->apitoken,
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
            'billingAddress'    => $this->clientInfo->adresse_residence,
            'locationAddress'   => new locationAddressRessource($this->clientLocationAddress),
            //'pays'              => $this->userLocationAddress->pays->nom,
            
            'wallet'            => new ClientCompteRessource($this->clientCompte),

        ];
    }
}

<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ClientDataRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        return [
            'clientId'          => $this->id,
            'groupeId'          => $this->groupe_id,
            'userType'          => 3,
            'lastName'          => $this->nom,
            'firstName'         => $this->prenom,
            'mobile'            => $this->username,
            'avatar'            => null,
            'actived'           => $this->clientInfo->etat,
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
            'locationAddress'   => new locationAddressRessource($this->clientLocationAddress),
            'wallet'            => new ClientCompteRessource($this->clientCompte),
        ];

    }
}
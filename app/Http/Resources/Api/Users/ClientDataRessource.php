<?php

namespace App\Http\Resources\Api\Users;

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
            'userId'            => $this->id,       // ----> Client ID
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
            'wallet'            => $this->clientCompte ? new ClientCompteRessource($this->clientCompte) : new \ArrayObject(),
        ];

    }
}
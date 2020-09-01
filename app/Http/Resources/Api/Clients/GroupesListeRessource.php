<?php

namespace App\Http\Resources\Api\Clients;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class GroupesListeRessource extends JsonResource
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
            'groupeId'              => $this->id,
            'groupeName'            => $this->nom,
            'shoppersNumber'        => $this->shoppers_in_groupe_count,
            'status'                => $this->etat,

            'location'              => [
                                        'daira'     => $this->daira,
                                        'latitude'  => $this->latitude, 
                                        'longitude' => $this->longitude
                                        ],

            'teamleader'            => [
                                        'id'        => $this->TeamleaderInGroupe->id,
                                        'lastName'  => $this->TeamleaderInGroupe->nom,
                                        'firstName' => $this->TeamleaderInGroupe->prenom,
                                        'mobile'    => $this->TeamleaderInGroupe->userInformation->mobile,
                                        'address'   => $this->TeamleaderInGroupe->userInformation->adresse_residence,
                                        'avatar'    => $this->TeamleaderInGroupe->userInformation->avatar,
                                        ],

        ];
    }
}

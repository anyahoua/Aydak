<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientPreferenceAchatRessource extends JsonResource
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
            'productId'     => $this->produit_id,
            'quantity'      => $this->quantite_produit,
            'state'         => $this->etat,
        ];
    }
}

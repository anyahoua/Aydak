<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderStateRessource extends JsonResource
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
            'orderCount'    => $this->count(),
            'type'          => $this->commande->situation->id,
            'stateName'     => $this->commande->situation->libely,
            
            /*
            'type'              => $this->situation_id,
            'stateName'         => $this->situation->libely,
            'ratio'             => 0,
            'orderCount'        => $this->userCommande,
            //'orderCount'        => $this->userCommande,
            */
/*            
            'orderId'           => $this->id,
            'customerId'        => $this->client->id,
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
            'state'             => $this->situation_id,
            'state_texte'       => $this->situation->libely,
            'total'             => null,

            'cartItems'         => CommandeItemRessource::collection($this->detailCommande),
            
            'customerData'      => new ClientDataRessource($this->client),
*/
        ];



    }
}

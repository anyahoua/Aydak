<?php

namespace App\Http\Resources\Api\Clients;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ClientOrdersRessource extends JsonResource
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
            
            'orderId'           => $this->id,
            'customerId'        => $this->client->id,
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d'),
            'stateId'           => $this->situation_id,
            'stateName'         => $this->situation->libely,
            'total'             => null, /* ??? */

            'cartItems'         => CommandeItemRessource::collection($this->detailCommande),
            

        ];

    }
}

<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OrdersRessource extends JsonResource
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
            'createdAtFr'       => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'createdAtEn'       => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'state'             => $this->situation_id,
            'stateName'         => $this->situation->libely,
            'total'             => $this->detailCommande->count(),

            'cartItems'         => CommandeItemRessource::collection($this->detailCommande),
            
            'customerData'      => new ClientDataRessource($this->client),

        ];

    }
}

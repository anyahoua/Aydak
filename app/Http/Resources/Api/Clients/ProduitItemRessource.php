<?php

namespace App\Http\Resources\Api\Clients;

use Illuminate\Http\Resources\Json\JsonResource;

class ProduitItemRessource extends JsonResource
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
            'productId'     => $this->id,
            'price'         => $this->prix->prix,
            //'historyPrice'         => $this->historyPrice,
            'productTitle'  => $this->libely,
            'comment'       => $this->commentaire,
            'unit'          => $this->unite_mesure_id,
        ];



    }
}

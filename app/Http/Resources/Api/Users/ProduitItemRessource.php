<?php

namespace App\Http\Resources\Api\Users;

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
            'price'         => (string) $this->prix->prix,
            //'historyPrice'         => $this->historyPrice,
            'productTitle'  => trim($this->libely),
            'comment'       => $this->commentaire,
            'unit'          => $this->unite_mesure_id,
        ];



    }
}

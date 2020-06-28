<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CommandeItemRessource extends JsonResource
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
            'quantity_ordered'      => $this->quantite_commande,
            'quantity_purchased'    => $this->quantite_achat,
            'price_purchased'       => $this->prix_u_achat,
            'purchasedOrNo'         => $this->etat,
            'product'               => new ProduitItemRessource($this->produit),
        ];


    }
}

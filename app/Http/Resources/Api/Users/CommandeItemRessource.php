<?php

namespace App\Http\Resources\Api\Users;

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
            'quantityOrdered'       => $this->quantite_commande ? (string) $this->quantite_commande : '0',
            'quantityPurchased'     => $this->quantite_achat ? (string) $this->quantite_achat : '0',
            'pricePurchased'        => (string) $this->prix_u_achat,
            //'amount'                => $this->prix_u_commande * $this->quantite_commande,
            'amount'                => (string) $this->amount(),
            'purchasedOrNo'         => $this->etat,
            'product'               => new ProduitItemRessource($this->produit),
        ];


    }
}

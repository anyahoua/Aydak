<?php

namespace App\Http\Resources\Api\Clients;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ClientCompteRessource extends JsonResource
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
            'walletId'      => $this->id,
            'clientId'      => $this->client_id,
            'debit'         => $this->debit,
            'credit'        => $this->credit,
            'newBalance'    => $this->nouveau_solde,
            'oldBalance'    => $this->ancien_solde,
            'state'         => $this->etat,
            'type'          => $this->type,
            'order'         => $this->commande ? $this->commande : 'null',
            'createdAt'     => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'updatedAt'     => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s'),
        ];
    }
}
<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserCompteRessource extends JsonResource
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
            'userId'        => $this->user_id,
            'newBalance'    => $this->nouveau_solde,
            'oldBalance'    => $this->ancien_solde,
            'createdAt'     => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'updatedAt'     => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s'),
            'state'         => $this->etat,
        ];
    }
}

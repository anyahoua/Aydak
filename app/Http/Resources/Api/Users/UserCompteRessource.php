<?php

namespace App\Http\Resources\Api\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserCompteRessource extends JsonResource
{

    // public function __construct($resource, $wallet)
    // {
    //     // Ensure you call the parent constructor
    //     parent::__construct($resource);
    //     $this->resource = $resource;
        
    //     $this->foo = $wallet;
    // }

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
            'debit'         => number_format($this->debit, 2, '.', ''),
            'credit'        => number_format($this->credit, 2, '.', ''),
            'newBalance'    => number_format($this->nouveau_solde, 2, '.', ''),
            'oldBalance'    => number_format($this->ancien_solde, 2, '.', ''),
            'createdAt'     => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'updatedAt'     => Carbon::parse($this->updated_at)->format('d-m-Y H:i:s'),
            'state'         => $this->etat,
        ];
    }
}

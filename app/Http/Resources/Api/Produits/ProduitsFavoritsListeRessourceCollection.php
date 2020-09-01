<?php

namespace App\Http\Resources\Api\Produits;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProduitsFavoritsListeRessourceCollection extends ResourceCollection
{
    public static $wrap = 'data';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
                    'status'    => 'Success',
                    'code'      => 200,
                    'message'   => 'Successfully',
                    'data'      => $this->collection,
                ];
    }
}

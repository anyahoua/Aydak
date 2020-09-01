<?php

namespace App\Http\Resources\Api\Produits;

use Illuminate\Http\Resources\Json\JsonResource;

class ProduitsListeRessource extends JsonResource
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

            'ProductId'         => $this->id,
            'ProductName'       => trim($this->libely),
            'unitMeasure'       => trim($this->uniteMesure->libely),
            'unitVal'           => trim($this->unite_val),
            'comment'           => trim($this->commentaire),
            'photo'             => trim($this->photo),
            'familyName'        => trim($this->famille->libely),
            'subCategoryName'   => trim($this->famille->sousCategorie->libely),
            'categoryName'      => trim($this->famille->sousCategorie->categorie->libely),
            'unitPrice'         => $this->prix ? $this->prix->prix : '',
            
        ];

    }
}

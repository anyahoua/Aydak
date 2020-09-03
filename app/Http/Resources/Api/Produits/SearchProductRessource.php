<?php

namespace App\Http\Resources\Api\Produits;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchProductRessource extends JsonResource
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
            'unitMeasure'       => trim($this->unite_mesure),
            'unitVal'           => trim($this->unite_val),
            'comment'           => trim($this->commentaire),
            'unitPrice'         => $this->prix ? (double) $this->prix : null,
            'photo'             => trim($this->photo),
            'familyId'          => $this->famille_id,
            'familyName'        => trim($this->famille),
            'subCategoryId'     => $this->sous_categorie_id,
            'subCategoryName'   => trim($this->sous_categorie),
            'categoryId'        => $this->categorie_id,
            'categoryName'      => trim($this->categorie),

        ];

    }
}
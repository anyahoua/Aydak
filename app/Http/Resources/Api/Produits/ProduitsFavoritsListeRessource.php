<?php

namespace App\Http\Resources\Api\Produits;

use Illuminate\Http\Resources\Json\JsonResource;

class ProduitsFavoritsListeRessource extends JsonResource
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
 
            'id'                => $this->id,
            'ProductId'         => $this->produit->id,
            'ProductName'       => trim($this->produit->libely),
            'unitMeasure'       => trim($this->produit->uniteMesure->libely),
            'unitVal'           => trim($this->produit->unite_val),
            'comment'           => trim($this->produit->commentaire),
            'photo'             => trim($this->produit->photo),
            'familyName'        => trim($this->produit->famille->libely),
            'subCategoryName'   => trim($this->produit->famille->sousCategorie->libely),
            'categoryName'      => trim($this->produit->famille->sousCategorie->categorie->libely),
            'unitPrice'         => $this->produit->prix ? $this->produit->prix->prix : '',
            
        ];

    }
}

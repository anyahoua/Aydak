<?php

namespace App\Http\Resources\Api\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class SousCategoriesRessource extends JsonResource
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
            'subCategoryId'     => $this->id,
            'categoryId'        => $this->categorie_id,
            'subCategoryName'   => trim($this->libely),
            'icon'              => $this->icon,
            'status'            => $this->etat,
        ];
    }
}

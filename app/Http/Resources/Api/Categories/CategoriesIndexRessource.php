<?php

namespace App\Http\Resources\Api\Categories;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesIndexRessource extends JsonResource
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
            'categoryId'        => $this->id,
            'categoryName'      => $this->libely,
            'icon'              => $this->icon,
            'status'            => $this->etat,
        ];
    }
}
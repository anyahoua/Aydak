<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class locationAddressRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $f_quartier     = $this->quartier ? $this->quartier.', ' : '';
        $f_commune      = $this->commune ? $this->commune.', ' : '';
        $f_daira        = $this->daira ? $this->daira.', ' : '';
        $f_wilaya       = $this->wilaya ? $this->wilaya.', ' : '';
        
        $fullAddress    = $f_quartier.$f_commune.$f_daira.$f_wilaya;

        return [
            'street'                => $this->quartier, // Rue
            'locality'              => $this->commune, // APC
            'administrativeArea'    => $this->daira, // Daira
            'state'                 => $this->wilaya, //  Wilaya
            'country'               => $this->pays->nom,             // Pays
            'location'              => null,
            'timezone'              => null,
            'fullAddress'       => $fullAddress,
            'location'              => [
                                        'latitude'=> $this->latitude, 
                                        'longitude'=> $this->longitude
                                        ],

        ];
    }
}
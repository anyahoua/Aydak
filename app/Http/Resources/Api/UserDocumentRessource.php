<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserDocumentRessource extends JsonResource
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
            'id'        => $this->id,
            'nameUrl'   => $this->doc,
            'createdAt' => Carbon::parse($this->created_at)->format('d-m-Y'),
        ];
    }
}

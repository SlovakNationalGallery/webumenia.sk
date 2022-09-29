<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'has_image' => $this->has_image,
            'biography' => $this->biography,
            'birth_place' => $this->birth_place,
            'death_place' => $this->death_place,
            'birth_date' => $this->birth_date,
            'death_date' => $this->death_date,
            'image_path' => $this->getImagePath(),
        ];
    }
}

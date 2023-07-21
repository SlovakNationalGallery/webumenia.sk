<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Resources\Json\JsonResource;
use App\ItemImage;

class ItemResource extends JsonResource
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
            'title' => $this->title,
            'authors' => $this->getUniqueAuthorsWithAuthorityNames(),
            'dating' => $this->getDatingFormated(true),
            'date_earliest' => $this->date_earliest,
            'date_latest' => $this->date_latest,
            'description' => $this->description,
            'authorities' => AuthorityResource::collection($this->authorities),
            'image_ratio' => $this->image_ratio,
            'images' => $this->images->map(function (ItemImage $image) {
                return ['deep_zoom_url' => $image->getDeepZoomUrl()];
            }),
        ];
    }
}

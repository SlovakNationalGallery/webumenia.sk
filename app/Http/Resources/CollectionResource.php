<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
            'header_image_src' => $this->header_image_src,
            'header_image_srcset' => $this->header_image_srcset,
            'filter_items_url' => $this->filter_items_url,
        ];
    }
}

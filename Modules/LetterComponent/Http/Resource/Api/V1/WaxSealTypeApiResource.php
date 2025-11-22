<?php

namespace Modules\LetterComponent\Http\Resource\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaxSealTypeApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => (string) $this->name,
            'price' => (float) $this->price,
            'is_premium' => (int) $this->is_premimum,
            'discount' => (float) $this->discount,
            'images' => (array) getImageUrls($this)
        ];
    }
}

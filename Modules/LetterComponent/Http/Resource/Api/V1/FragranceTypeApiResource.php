<?php

namespace Modules\LetterComponent\Http\Resource\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FragranceTypeApiResource extends JsonResource
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
            'description' => (string) $this->description,
            'is_premimum' => (int) $this->is_premium,
            'price' => (float) $this->price,
            'discount' => (float) $this->discount,
            'images' => (array) getImageUrls($this)
        ];
    }
}

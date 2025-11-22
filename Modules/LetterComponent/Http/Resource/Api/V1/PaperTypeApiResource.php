<?php

namespace Modules\LetterComponent\Http\Resource\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaperTypeApiResource extends JsonResource
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
            'stock' => (int) $this->stock,
            'price' => (float) $this->price_per_page,
            'description' => (string) $this->description,
            'is_premium' => (int) $this->is_premimum,
            'discount' => (float) $this->discount,
            'images' => (array) getImageUrls($this)
        ];
    }
}

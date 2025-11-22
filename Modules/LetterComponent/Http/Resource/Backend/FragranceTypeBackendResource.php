<?php

namespace Modules\LetterComponent\Http\Resource\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FragranceTypeBackendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['created_at'] = $this->created_at?->diffForHumans();
        return $data;
    }
}

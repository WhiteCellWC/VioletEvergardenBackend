<?php

namespace Modules\LetterComponent\DTO;

use App\Models\WaxSealType;
use Illuminate\Http\Request;

class WaxSealTypeDto
{
    public function __construct(
        public ?int $id,
        public ?int $userId,
        public string $name,
        public array $images,
        public ?array $deleteImages,
        public ?bool $isCustom,
        public float $price,
        public ?bool $isPremium,
        public ?float $discount,
        public ?bool $status
    ) {}

    public function toArray()
    {
        return [
            WaxSealType::id => $this->id,
            WaxSealType::userId => $this->userId,
            WaxSealType::name => $this->name,
            WaxSealType::isCustom => $this->isCustom,
            WaxSealType::price => $this->price,
            WaxSealType::isPremium => $this->isPremium,
            WaxSealType::discount => $this->discount,
            WaxSealType::status => $this->status
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            $id,
            $request->user_id,
            $request->name,
            $request->images ?? [],
            $request->delete_images,
            $request->user_id ? true : false,
            $request->price,
            $request->is_premium ?? false,
            $request->discount,
            $request->status ?? true
        );
    }
}

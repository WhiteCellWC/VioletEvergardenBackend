<?php

namespace Modules\LetterComponent\DTO;

use App\Models\WaxSealType;
use Illuminate\Http\Request;

class SearchWaxSealTypeDto
{
    public function __construct(
        public ?int $userId,
        public ?string $name,
        public ?bool $isCustom,
        public ?float $price,
        public ?bool $isPremium,
        public ?float $discount,
        public ?bool $status
    ) {}

    public function toArray()
    {
        return [
            WaxSealType::userId => $this->userId,
            WaxSealType::name => $this->name,
            WaxSealType::isCustom => $this->isCustom,
            WaxSealType::price => $this->price,
            WaxSealType::isPremium => $this->isPremium,
            WaxSealType::discount => $this->discount,
            WaxSealType::status => $this->status
        ];
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            $request->user_id,
            $request->name,
            $request->is_custom,
            $request->price,
            $request->is_premium,
            $request->discount,
            $request->status
        );
    }
}

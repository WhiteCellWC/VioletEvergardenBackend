<?php

namespace Modules\Delivery\DTO;

use App\Models\DeliveryTier;
use Illuminate\Http\Request;

class DeliveryTierDto
{
    public function __construct(
        public ?int $id,
        public string $deliveryOptionId,
        public float $maxWeight,
        public float $price,
        public ?bool $status
    ) {}

    public function toArray()
    {
        return [
            DeliveryTier::id => $this->id,
            DeliveryTier::deliveryOptionId => $this->deliveryOptionId,
            DeliveryTier::maxWeight => $this->maxWeight,
            DeliveryTier::price => $this->price,
            DeliveryTier::status => $this->status
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            $id,
            $request->delivery_option_id,
            $request->max_weight,
            $request->price,
            $request->status ?? true
        );
    }
}

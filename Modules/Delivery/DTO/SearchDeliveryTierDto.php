<?php

namespace Modules\Delivery\DTO;

use App\Models\DeliveryTier;
use Illuminate\Http\Request;

class SearchDeliveryTierDto
{
    public function __construct(
        public ?string $deliveryOptionId,
        public ?float $maxWeight,
        public ?float $price,
        public ?bool $status
    ) {}

    public function toArray()
    {
        return [
            DeliveryTier::deliveryOptionId => $this->deliveryOptionId,
            DeliveryTier::maxWeight => $this->maxWeight,
            DeliveryTier::price => $this->price,
            DeliveryTier::status => $this->status
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            $request->delivery_option_id,
            $request->max_weight,
            $request->price,
            $request->status
        );
    }
}

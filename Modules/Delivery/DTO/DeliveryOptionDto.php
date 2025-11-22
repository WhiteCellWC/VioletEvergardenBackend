<?php

namespace Modules\Delivery\DTO;

use App\Models\DeliveryOption;
use Illuminate\Http\Request;

class DeliveryOptionDto
{
    public function __construct(
        public ?int $id,
        public string $name,
        public bool $isWeightBased,
        public float $base_cost,
        public ?string $deliveryType,
        public float $estimatedDays,
        public bool $hasTracking,
        public ?bool $status
    ) {}

    public function toArray()
    {
        return [
            DeliveryOption::id => $this->id,
            DeliveryOption::name => $this->name,
            DeliveryOption::isWeightBased => $this->isWeightBased,
            DeliveryOption::baseCost => $this->base_cost,
            DeliveryOption::deliveryType => $this->deliveryType,
            DeliveryOption::estimatedDays => $this->estimatedDays,
            DeliveryOption::hasTracking => $this->hasTracking,
            DeliveryOption::status => $this->status
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            $id,
            $request->name,
            $request->is_weight_based,
            $request->base_cost,
            $request->delivery_type ?? null,
            $request->estimated_days,
            $request->has_tracking ?? false,
            $request->status ?? true
        );
    }
}

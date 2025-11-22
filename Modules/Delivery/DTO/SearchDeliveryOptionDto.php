<?php

namespace Modules\Delivery\DTO;

use App\Models\DeliveryOption;
use Illuminate\Http\Request;

class SearchDeliveryOptionDto
{
    public function __construct(
        public ?string $name,
        public ?bool $isWeightBased,
        public ?float $baseCost,
        public ?string $deliveryType,
        public ?float $estimatedDays,
        public ?bool $hasTracking,
        public ?bool $status
    ) {}

    public function toArray()
    {
        return [
            DeliveryOption::name => $this->name,
            DeliveryOption::isWeightBased => $this->isWeightBased,
            DeliveryOption::baseCost => $this->baseCost,
            DeliveryOption::deliveryType => $this->deliveryType,
            DeliveryOption::estimatedDays => $this->estimatedDays,
            DeliveryOption::hasTracking => $this->hasTracking,
            DeliveryOption::status => $this->status
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            $request->name,
            $request->is_weight_based,
            $request->base_cost,
            $request->delivery_type,
            $request->estimated_days,
            $request->has_tracking,
            $request->status
        );
    }
}

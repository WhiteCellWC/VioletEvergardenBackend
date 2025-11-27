<?php

namespace Modules\Delivery\DTO;

use App\Models\LetterDelivery;
use Illuminate\Http\Request;

class LetterDeliveryDto
{
    public function __construct(
        public ?int $id,
        public int $recipientId,
        public int $deliveryOptionId,
        public int $deliveryTierId,
        public float $deliveryCost,
        public ?string $trackingNumber,
        public string $deliveryStatus,
        public string $scheduledAt,
        public ?string $shippedAt,
        public ?string $deliveredAt
    ) {}

    public function toArray()
    {
        return [
            LetterDelivery::id => $this->id,
            LetterDelivery::recipientId => $this->recipientId,
            LetterDelivery::deliveryOptionId => $this->deliveryOptionId,
            LetterDelivery::deliveryTierId => $this->deliveryTierId,
            LetterDelivery::deliveryCost => $this->deliveryCost,
            LetterDelivery::trackingNumber => $this->trackingNumber,
            LetterDelivery::deliveryStatus => $this->deliveryStatus,
            LetterDelivery::scheduledAt => $this->scheduledAt,
            LetterDelivery::shippedAt => $this->shippedAt,
            LetterDelivery::deliveredAt => $this->deliveredAt
        ];
    }

    public static function fromRequest(Request $request, $id = null)
    {
        return new self(
            $id,
            $request->recipient_id,
            $request->delivery_option_id,
            $request->delivery_tier_id,
            $request->delivery_cost,
            $request->tracking_number ?? null,
            $request->delivery_status,
            $request->scheduled_date,
            $request->shipped_at ?? null,
            $request->delivered_at ?? null,
        );
    }
}

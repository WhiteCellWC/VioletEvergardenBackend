<?php

namespace Modules\Delivery\Action\Shipment;

use App\Models\Letter;
use App\Models\LetterDelivery;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Delivery\Contract\LetterDeliveryServiceInterface;
use Modules\Delivery\DTO\LetterDeliveryDto;
use Modules\Delivery\Http\Cache\LetterDeliveryCache;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\Letter\DTO\LetterDto;
use Modules\Letter\Http\Cache\LetterCache;

class UpdateShipmentAction
{
    public function __construct(
        protected LetterDeliveryServiceInterface $letterDeliveryService,
        protected LetterServiceInterface $letterService
    ) {}

    public function handle(Request $request, string $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $recipients = $request->input('recipients', []);

            foreach ($recipients as $recipientData) {
                $existingDelivery = LetterDelivery::findOrFail($recipientData['letter_delivery_id']);

                if ($existingDelivery->recipient_id !== (int)$recipientData['recipient_id']) {
                    abort(400, 'Recipient ID mismatch.');
                }

                $status = is_bool($recipientData['delivery_status'])
                    ? ($recipientData['delivery_status'] ? 'delivered' : 'pending')
                    : $recipientData['delivery_status'];

                $letterDeliveryDto = new LetterDeliveryDto(
                    $existingDelivery->id,
                    $existingDelivery->recipient_id,
                    $existingDelivery->delivery_option_id,
                    $existingDelivery->delivery_tier_id,
                    $existingDelivery->delivery_cost,
                    $existingDelivery->tracking_number,
                    $status,
                    $existingDelivery->scheduled_at,
                    $existingDelivery->shipped_at,
                    $existingDelivery->delivered_at
                );

                $this->letterDeliveryService->update($letterDeliveryDto);

                Cache::forget(LetterDeliveryCache::getCacheKey(LetterDeliveryCache::GET, $recipientData['letter_delivery_id']));
            }

            Cache::forget(LetterDeliveryCache::getCacheKey(LetterDeliveryCache::GET_ALL));

            $letter = $this->letterService->get($id, [Letter::recipients . '.' . Recipient::letterDeliveries]);

            $allDelivered = collect($letter->recipients)->every(function ($recipient) {
                return $recipient->letterDeliveries->every(function ($delivery) {
                    return $delivery->delivery_status === 'delivered';
                });
            });

            if ($allDelivered && !$letter->is_sent) {
                $letterDto = new LetterDto(['is_sent' => 1]);
                $this->letterService->update($letterDto, $id);

                Cache::forget(LetterCache::getCacheKey(LetterCache::GET_ALL));
                Cache::forget(LetterCache::getCacheKey(LetterCache::GET, $id));
            }

            return $this->letterService->get($id);
        });
    }
}

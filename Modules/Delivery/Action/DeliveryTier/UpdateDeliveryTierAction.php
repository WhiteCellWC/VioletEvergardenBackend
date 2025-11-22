<?php

namespace Modules\Delivery\Action\DeliveryTier;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\DTO\DeliveryTierDto;
use Modules\Delivery\Http\Cache\DeliveryTierCache;

class UpdateDeliveryTierAction
{
    public function __construct(
        protected DeliveryTierServiceInterface $deliveryTierService,
    ) {}

    public function handle(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $deliveryTierDto = DeliveryTierDto::fromRequest($request, $id);

            $deliveryTier = $this->deliveryTierService->update($deliveryTierDto);

            Cache::tags([DeliveryTierCache::GET_ALL, DeliveryTierCache::GET . '_' . $id])->flush();
            DB::commit();

            return $deliveryTier;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

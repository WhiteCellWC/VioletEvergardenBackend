<?php

namespace Modules\Delivery\Action\DeliveryTier;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\Http\Cache\DeliveryTierCache;
use Throwable;

class DeleteDeliveryTierAction
{
    public function __construct(
        protected DeliveryTierServiceInterface $deliveryTierService,
    ) {}

    public function handle(string $id)
    {
        DB::beginTransaction();
        try {
            $deliveryTier = $this->deliveryTierService->get($id);

            $deliveryTierWeight = $this->deliveryTierService->delete($deliveryTier);

            Cache::tags([DeliveryTierCache::GET_ALL, DeliveryTierCache::GET . '_' . $id])->flush();
            DB::commit();

            return $deliveryTierWeight;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

<?php

namespace Modules\Delivery\Action\DeliveryOption;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\Http\Cache\DeliveryOptionCache;
use Throwable;

class DeleteDeliveryOptionAction
{
    public function __construct(
        protected DeliveryOptionServiceInterface $deliveryOptionService,
    ) {}

    public function handle(string $id)
    {
        DB::beginTransaction();
        try {
            $deliveryOption = $this->deliveryOptionService->get($id);

            $deliveryOptionWeight = $this->deliveryOptionService->delete($deliveryOption);

            Cache::tags([DeliveryOptionCache::GET_ALL, DeliveryOptionCache::GET . '_' . $id])->flush();
            DB::commit();

            return $deliveryOptionWeight;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

<?php

namespace Modules\Delivery\Action\DeliveryOption;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\DTO\DeliveryOptionDto;
use Modules\Delivery\Http\Cache\DeliveryOptionCache;

class UpdateDeliveryOptionAction
{
    public function __construct(
        protected DeliveryOptionServiceInterface $deliveryOptionService,
    ) {}

    public function handle(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $deliveryOptionDto = DeliveryOptionDto::fromRequest($request, $id);

            $deliveryOption = $this->deliveryOptionService->update($deliveryOptionDto);

            Cache::tags([DeliveryOptionCache::GET_ALL, DeliveryOptionCache::GET . '_' . $id])->flush();
            DB::commit();

            return $deliveryOption;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

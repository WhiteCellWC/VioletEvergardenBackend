<?php

namespace Modules\Delivery\Action\DeliveryOption;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\DTO\DeliveryOptionDto;
use Modules\Delivery\Http\Cache\DeliveryOptionCache;
use Throwable;

class CreateDeliveryOptionAction
{
    public function __construct(
        protected DeliveryOptionServiceInterface $deliveryOptionService,
    ) {}

    public function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            $deliveryOptionDto = DeliveryOptionDto::fromRequest($request);

            $deliveryOption = $this->deliveryOptionService->create($deliveryOptionDto);

            Cache::tags([DeliveryOptionCache::GET_ALL])->flush();
            DB::commit();

            return $deliveryOption;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

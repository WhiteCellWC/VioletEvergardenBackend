<?php

namespace Modules\Delivery\Action\DeliveryTier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\DTO\DeliveryTierDto;
use Modules\Delivery\Http\Cache\DeliveryTierCache;
use Throwable;

class CreateDeliveryTierAction
{
    public function __construct(
        protected DeliveryTierServiceInterface $deliveryTierService,
    ) {}

    public function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            $deliveryTierDto = DeliveryTierDto::fromRequest($request);

            $deliveryTier = $this->deliveryTierService->create($deliveryTierDto);

            Cache::tags([DeliveryTierCache::GET_ALL])->flush();
            DB::commit();

            return $deliveryTier;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

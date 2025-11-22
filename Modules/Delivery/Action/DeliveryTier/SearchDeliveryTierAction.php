<?php

namespace Modules\Delivery\Action\DeliveryTier;

use Throwable;
use Illuminate\Http\Request;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\DTO\DeliveryTierDto;
use Modules\Shared\DTO\QueryOptionsDto;

class SearchDeliveryTierAction
{
    public function __construct(protected DeliveryTierServiceInterface $deliveryTierService) {}

    public function handle(Request $request, array|string|null $relation = null)
    {
        try {
            $condsIn = DeliveryTierDto::fromRequest($request)->toArray();

            $queryOptions = QueryOptionsDto::fromRequest($request)->toArray();

            $deliveryTiers = $this->deliveryTierService->getAll($relation, $condsIn, $request->condsNotIn, $queryOptions);

            return $deliveryTiers;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}

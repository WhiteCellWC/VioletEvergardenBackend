<?php

namespace Modules\Delivery\Action\DeliveryOption;

use Throwable;
use Illuminate\Http\Request;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\DTO\SearchDeliveryOptionDto;
use Modules\Shared\DTO\QueryOptionsDto;

class SearchDeliveryOptionAction
{
    public function __construct(protected DeliveryOptionServiceInterface $deliveryOptionService) {}

    public function handle(Request $request, array|string|null $relation = null)
    {
        try {
            $condsIn = SearchDeliveryOptionDto::fromRequest($request)->toArray();

            $queryOptions = QueryOptionsDto::fromRequest($request)->toArray();

            $deliveryOptions = $this->deliveryOptionService->getAll($relation, $condsIn, $request->condsNotIn, $queryOptions);

            return $deliveryOptions;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}

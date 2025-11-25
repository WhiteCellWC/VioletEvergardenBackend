<?php

namespace Modules\Delivery\Http\Controller\Api\V1;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Delivery\Action\DeliveryTier\SearchDeliveryTierAction;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\Http\Resource\Api\V1\DeliveryTierApiResource;

class DeliveryTierApiController extends Controller
{
    public function __construct(
        protected SearchDeliveryTierAction $searchDeliveryTierAction,
        protected DeliveryTierServiceInterface $deliveryTierService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $deliveryTiers = $this->searchDeliveryTierAction->handle($request);

            return DeliveryTierApiResource::collection($deliveryTiers);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $deliveryTier = $this->deliveryTierService->get($id);

            return new DeliveryTierApiResource($deliveryTier);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

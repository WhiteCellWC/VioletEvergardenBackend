<?php

namespace Modules\Delivery\Http\Controller\Api\V1;

use Modules\Delivery\Http\Resource\Api\V1\DeliveryOptionApiResource;
use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeliveryOption;
use Modules\Delivery\Action\DeliveryOption\SearchDeliveryOptionAction;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;

class DeliveryOptionApiController extends Controller
{
    public function __construct(
        protected SearchDeliveryOptionAction $searchDeliveryOptionAction,
        protected DeliveryOptionServiceInterface $deliveryOptionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $deliveryOptions = $this->searchDeliveryOptionAction->handle($request);

            $deliveryOptions->loadCount(DeliveryOption::deliveryTiers);

            return DeliveryOptionApiResource::collection($deliveryOptions);
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
            $deliveryOption = $this->deliveryOptionService->get($id);

            return new DeliveryOptionApiResource($deliveryOption);
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

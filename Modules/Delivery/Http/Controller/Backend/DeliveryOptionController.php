<?php

namespace Modules\Delivery\Http\Controller\Backend;

use App\Enums\FlagType;
use App\Http\Controllers\Controller;
use App\Models\DeliveryOption;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Delivery\Action\DeliveryOption\CreateDeliveryOptionAction;
use Modules\Delivery\Action\DeliveryOption\DeleteDeliveryOptionAction;
use Modules\Delivery\Action\DeliveryOption\SearchDeliveryOptionAction;
use Modules\Delivery\Action\DeliveryOption\UpdateDeliveryOptionAction;
use Modules\Delivery\Contract\DeliveryOptionServiceInterface;
use Modules\Delivery\Http\Request\Backend\DeliveryOption\StoreDeliveryOptionRequest;
use Modules\Delivery\Http\Request\Backend\DeliveryOption\UpdateDeliveryOptionRequest;
use Modules\Delivery\Http\Resource\Backend\DeliveryOptionBackendResource;
use Throwable;

class DeliveryOptionController extends Controller
{
    public function __construct(
        protected SearchDeliveryOptionAction $searchDeliveryOptionAction,
        protected CreateDeliveryOptionAction $createDeliveryOptionAction,
        protected UpdateDeliveryOptionAction $updateDeliveryOptionAction,
        protected DeleteDeliveryOptionAction $deleteDeliveryOptionAction,
        protected DeliveryOptionServiceInterface $deliveryOptionService
    ) {}

    public const parentPath = 'DeliveryOption';

    public const indexPath = self::parentPath . '/Index';

    public const editPath = self::parentPath . '/Edit';

    public const createPath = self::parentPath . '/Create';

    protected $backendRelation = [];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $deliveryOptions = $this->searchDeliveryOptionAction->handle($request, $this->backendRelation);

            $deliveryOptions->loadCount(DeliveryOption::deliveryTiers);

            return Inertia::render(self::indexPath, [
                'deliveryOptions' => DeliveryOptionBackendResource::collection($deliveryOptions)
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return Inertia::render(self::createPath);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeliveryOptionRequest $request)
    {
        try {
            $this->createDeliveryOptionAction->handle($request);

            return redirectView('delivery-options.index', 'Delivery Option created successfully!', FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $deliveryOption = $this->deliveryOptionService->get($id);

            return Inertia::render(self::editPath, [
                'deliveryOption' => new DeliveryOptionBackendResource($deliveryOption)
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveryOptionRequest $request, string $id)
    {
        try {
            $this->updateDeliveryOptionAction->handle($request, $id);

            return redirectView('delivery-options.index', 'Delivery Option updated successfully!', FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $name = $this->deleteDeliveryOptionAction->handle($id);

            return redirectView('delivery-options.index', "$name deleted successfully!", FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

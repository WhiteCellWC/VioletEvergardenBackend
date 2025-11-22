<?php

namespace Modules\Delivery\Http\Controller\Backend;

use App\Enums\FlagType;
use App\Http\Controllers\Controller;
use App\Models\DeliveryTier;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Delivery\Action\DeliveryTier\CreateDeliveryTierAction;
use Modules\Delivery\Action\DeliveryTier\DeleteDeliveryTierAction;
use Modules\Delivery\Action\DeliveryTier\SearchDeliveryTierAction;
use Modules\Delivery\Action\DeliveryTier\UpdateDeliveryTierAction;
use Modules\Delivery\Contract\DeliveryTierServiceInterface;
use Modules\Delivery\Http\Request\Backend\DeliveryTier\StoreDeliveryTierRequest;
use Modules\Delivery\Http\Request\Backend\DeliveryTier\UpdateDeliveryTierRequest;
use Modules\Delivery\Http\Resource\Backend\DeliveryTierBackendResource;
use Throwable;

class DeliveryTierController extends Controller
{
    public function __construct(
        protected SearchDeliveryTierAction $searchDeliveryTierAction,
        protected CreateDeliveryTierAction $createDeliveryTierAction,
        protected UpdateDeliveryTierAction $updateDeliveryTierAction,
        protected DeleteDeliveryTierAction $deleteDeliveryTierAction,
        protected DeliveryTierServiceInterface $deliveryTierService
    ) {}

    public const parentPath = 'DeliveryTier';

    public const indexPath = self::parentPath . '/Index';

    public const editPath = self::parentPath . '/Edit';

    public const createPath = self::parentPath . '/Create';

    protected $backendRelation = [DeliveryTier::deliveryOption];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $deliveryTiers = $this->searchDeliveryTierAction->handle($request, $this->backendRelation);

            return Inertia::render(self::indexPath, [
                'deliveryTiers' => DeliveryTierBackendResource::collection($deliveryTiers)
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
    public function store(StoreDeliveryTierRequest $request)
    {
        try {
            $this->createDeliveryTierAction->handle($request);

            return redirectView('delivery-tiers.index', 'Delivery Tier created successfully!', FlagType::SUCCESS);
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
            $deliveryTier = $this->deliveryTierService->get($id, $this->backendRelation);

            return Inertia::render(self::editPath, [
                'deliveryTier' => new DeliveryTierBackendResource($deliveryTier)
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveryTierRequest $request, string $id)
    {
        try {
            $this->updateDeliveryTierAction->handle($request, $id);

            return redirectView('delivery-tiers.index', 'Delivery Tier updated successfully!', FlagType::SUCCESS);
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
            $name = $this->deleteDeliveryTierAction->handle($id);

            return redirectView('delivery-tiers.index', "$name deleted successfully!", FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

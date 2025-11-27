<?php

namespace Modules\Delivery\Http\Controller\Backend;

use App\Enums\FlagType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Delivery\Action\Shipment\SearchShipmentAction;
use Modules\Delivery\Action\Shipment\UpdateShipmentAction;
use Modules\Delivery\Http\Request\Backend\Shipment\UpdateShipmentRequest;
use Modules\Delivery\Http\Resource\Backend\ShipmentBackendResource;
use Modules\Letter\Contract\LetterServiceInterface;
use Throwable;

class ShipmentController extends Controller
{
    public function __construct(
        protected SearchShipmentAction $searchShipmentAction,
        protected LetterServiceInterface $letterService,
        protected UpdateShipmentAction $updateShipmentAction
    ) {}

    public const parentPath = 'Shipment';

    public const indexPath = self::parentPath . '/Index';

    public const editPath = self::parentPath . '/Edit';

    public const createPath = self::parentPath . '/Create';

    protected $backendRelation = [Letter::recipients, Letter::recipients . '.' . Recipient::letterDeliveries, Letter::user];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $shipments = $this->searchShipmentAction->handle($request, $this->backendRelation);

            return Inertia::render(self::indexPath, [
                'shipments' => ShipmentBackendResource::collection($shipments)
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
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $letter = $this->letterService->get($id, $this->backendRelation);
            return Inertia::render(self::editPath, ['shipment' => new ShipmentBackendResource($letter)]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipmentRequest $request, string $id)
    {
        try {
            $this->updateShipmentAction->handle($request, $id);
            return Helper::redirectView('shipments.index', 'Shipment updated successfully!', FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

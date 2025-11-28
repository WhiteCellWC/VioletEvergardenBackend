<?php

namespace Modules\Delivery\Http\Controller\Api\V1;

use Modules\Delivery\Http\Resource\Api\V1\ShipmentApiResource;
use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\Recipient;
use Modules\Delivery\Action\Shipment\SearchShipmentAction;
use Modules\Letter\Contract\LetterServiceInterface;

class ShipmentApiController extends Controller
{
    protected $backendRelation = [Letter::recipients, Letter::recipients . '.' . Recipient::letterDeliveries, Letter::user];

    public function __construct(
        protected SearchShipmentAction $searchShipmentAction,
        protected LetterServiceInterface $letterService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $shipments = $this->searchShipmentAction->handle($request, $this->backendRelation);

            return ShipmentApiResource::collection($shipments);
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
            $letter = $this->letterService->get($id, $this->backendRelation);

            return new ShipmentApiResource($letter);
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

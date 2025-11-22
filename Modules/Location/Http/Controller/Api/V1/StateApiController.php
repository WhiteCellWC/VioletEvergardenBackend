<?php

namespace Modules\Location\Http\Controller\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Location\Action\State\CreateStateAction;
use Modules\Location\Action\State\DeleteStateAction;
use Modules\Location\Action\State\SearchStateAction;
use Modules\Location\Action\State\UpdateStateAction;
use Modules\Location\Contract\StateServiceInterface;
use Modules\Location\Http\Request\Api\V1\State\StoreStateApiRequest;
use Modules\Location\Http\Request\Api\V1\State\UpdateStateApiRequest;
use Modules\Location\Http\Resource\Api\V1\StateApiResource;
use Throwable;

class StateApiController extends Controller
{
    public function __construct(
        protected SearchStateAction $searchStateAction,
        protected CreateStateAction $createStateAction,
        protected UpdateStateAction $updateStateAction,
        protected DeleteStateAction $deleteStateAction,
        protected StateServiceInterface $stateService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $states = $this->searchStateAction->handle($request);

            return StateApiResource::collection($states);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStateApiRequest $request)
    {
        try {
            $state = $this->createStateAction->handle($request);

            return new StateApiResource($state);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $state = $this->stateService->get($id);

            return new StateApiResource($state);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStateApiRequest $request, string $id)
    {
        try {
            $state = $this->updateStateAction->handle($request, $id);

            return new StateApiResource($state);
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
            $stateName = $this->deleteStateAction->handle($id);

            return response()->json($stateName);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

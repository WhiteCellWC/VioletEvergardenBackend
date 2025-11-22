<?php

namespace Modules\LetterComponent\Http\Controller\Api\V1;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EnvelopeType;
use Modules\LetterComponent\Action\EnvelopeType\CreateEnvelopeTypeAction;
use Modules\LetterComponent\Action\EnvelopeType\DeleteEnvelopeTypeAction;
use Modules\LetterComponent\Action\EnvelopeType\SearchEnvelopeTypeAction;
use Modules\LetterComponent\Action\EnvelopeType\UpdateEnvelopeTypeAction;
use Modules\LetterComponent\Contract\EnvelopeTypeServiceInterface;
use Modules\LetterComponent\Http\Request\Api\V1\EnvelopeType\StoreEnvelopeTypeApiRequest;
use Modules\LetterComponent\Http\Request\Api\V1\EnvelopeType\UpdateEnvelopeTypeApiRequest;
use Modules\LetterComponent\Http\Resource\Api\V1\EnvelopeTypeApiResource;

class EnvelopeTypeApiController extends Controller
{
    protected $apiRelation = [EnvelopeType::images];

    public function __construct(
        protected SearchEnvelopeTypeAction $searchEnvelopeTypeAction,
        protected CreateEnvelopeTypeAction $createEnvelopeTypeAction,
        protected UpdateEnvelopeTypeAction $updateEnvelopeTypeAction,
        protected DeleteEnvelopeTypeAction $deleteEnvelopeTypeAction,
        protected EnvelopeTypeServiceInterface $envelopeTypeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $envelopeTypes = $this->searchEnvelopeTypeAction->handle($request, $this->apiRelation);

            return EnvelopeTypeApiResource::collection($envelopeTypes);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnvelopeTypeApiRequest $request)
    {
        try {
            $envelopeType = $this->createEnvelopeTypeAction->handle($request);

            return new EnvelopeTypeApiResource($envelopeType);
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
            $envelopeType = $this->envelopeTypeService->get($id);

            return new EnvelopeTypeApiResource($envelopeType);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnvelopeTypeApiRequest $request, string $id)
    {
        try {
            $envelopeType = $this->updateEnvelopeTypeAction->handle($request, $id);

            return new EnvelopeTypeApiResource($envelopeType);
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
            $envelopeTypeName = $this->deleteEnvelopeTypeAction->handle($id);

            return response()->json($envelopeTypeName);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

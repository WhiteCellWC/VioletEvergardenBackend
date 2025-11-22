<?php

namespace Modules\Letter\Http\Controller\Api\V1;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LetterType;
use Modules\Letter\Action\LetterType\CreateLetterTypeAction;
use Modules\Letter\Action\LetterType\DeleteLetterTypeAction;
use Modules\Letter\Action\LetterType\SearchLetterTypeAction;
use Modules\Letter\Action\LetterType\UpdateLetterTypeAction;
use Modules\Letter\Contract\LetterTypeServiceInterface;
use Modules\Letter\Http\Request\Api\LetterType\StoreLetterTypeApiRequest;
use Modules\Letter\Http\Request\Api\LetterType\UpdateLetterTypeApiRequest;
use Modules\Letter\Http\Resource\Api\V1\LetterTypeApiResource;

class LetterTypeApiController extends Controller
{
    public function __construct(
        protected SearchLetterTypeAction $searchLetterTypeAction,
        protected CreateLetterTypeAction $createLetterTypeAction,
        protected UpdateLetterTypeAction $updateLetterTypeAction,
        protected DeleteLetterTypeAction $deleteLetterTypeAction,
        protected LetterTypeServiceInterface $letterTypeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $letterTypes = $this->searchLetterTypeAction->handle($request);

            $letterTypes->loadCount(LetterType::letterTemplates);

            return LetterTypeApiResource::collection($letterTypes);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLetterTypeApiRequest $request)
    {
        try {
            $letterType = $this->createLetterTypeAction->handle($request);

            return new LetterTypeApiResource($letterType);
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
            $letterType = $this->letterTypeService->get($id);

            return new LetterTypeApiResource($letterType);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLetterTypeApiRequest $request, string $id)
    {
        try {
            $letterType = $this->updateLetterTypeAction->handle($request, $id);

            return new LetterTypeApiResource($letterType);
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
            $letterTypeName = $this->deleteLetterTypeAction->handle($id);

            return response()->json($letterTypeName);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

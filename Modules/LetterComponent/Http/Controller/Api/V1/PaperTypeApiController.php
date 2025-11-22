<?php

namespace Modules\LetterComponent\Http\Controller\Api\V1;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaperType;
use Modules\LetterComponent\Action\PaperType\CreatePaperTypeAction;
use Modules\LetterComponent\Action\PaperType\DeletePaperTypeAction;
use Modules\LetterComponent\Action\PaperType\SearchPaperTypeAction;
use Modules\LetterComponent\Action\PaperType\UpdatePaperTypeAction;
use Modules\LetterComponent\Contract\PaperTypeServiceInterface;
use Modules\LetterComponent\Http\Request\Api\V1\PaperType\StorePaperTypeApiRequest;
use Modules\LetterComponent\Http\Request\Api\V1\PaperType\UpdatePaperTypeApiRequest;
use Modules\LetterComponent\Http\Resource\Api\V1\PaperTypeApiResource;

class PaperTypeApiController extends Controller
{
    protected $apiRelation = [PaperType::images];

    public function __construct(
        protected SearchPaperTypeAction $searchPaperTypeAction,
        protected CreatePaperTypeAction $createPaperTypeAction,
        protected UpdatePaperTypeAction $updatePaperTypeAction,
        protected DeletePaperTypeAction $deletePaperTypeAction,
        protected PaperTypeServiceInterface $paperTypeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $paperTypes = $this->searchPaperTypeAction->handle($request, $this->apiRelation);

            return PaperTypeApiResource::collection($paperTypes);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaperTypeApiRequest $request)
    {
        try {
            $paperType = $this->createPaperTypeAction->handle($request);

            return new PaperTypeApiResource($paperType);
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
            $paperType = $this->paperTypeService->get($id);

            return new PaperTypeApiResource($paperType);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaperTypeApiRequest $request, string $id)
    {
        try {
            $paperType = $this->updatePaperTypeAction->handle($request, $id);

            return new PaperTypeApiResource($paperType);
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
            $paperTypeName = $this->deletePaperTypeAction->handle($id);

            return response()->json($paperTypeName);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

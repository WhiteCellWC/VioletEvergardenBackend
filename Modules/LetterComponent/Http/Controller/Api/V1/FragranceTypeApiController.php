<?php

namespace Modules\LetterComponent\Http\Controller\Api\V1;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FragranceType;
use Modules\LetterComponent\Http\Resource\Api\V1\FragranceTypeApiResource;
use Modules\LetterComponent\Contract\FragranceTypeServiceInterface;
use Modules\LetterComponent\Action\FragranceType\CreateFragranceTypeAction;
use Modules\LetterComponent\Action\FragranceType\DeleteFragranceTypeAction;
use Modules\LetterComponent\Action\FragranceType\SearchFragranceTypeAction;
use Modules\LetterComponent\Action\FragranceType\UpdateFragranceTypeAction;
use Modules\LetterComponent\Http\Request\Api\V1\FragranceType\StoreFragranceTypeApiRequest;
use Modules\LetterComponent\Http\Request\Api\V1\FragranceType\UpdateFragranceTypeApiRequest;

class FragranceTypeApiController extends Controller
{
    protected $apiRelation = [FragranceType::images];

    public function __construct(
        protected SearchFragranceTypeAction $searchFragranceTypeAction,
        protected CreateFragranceTypeAction $createFragranceTypeAction,
        protected UpdateFragranceTypeAction $updateFragranceTypeAction,
        protected DeleteFragranceTypeAction $deleteFragranceTypeAction,
        protected FragranceTypeServiceInterface $fragranceTypeService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $fragranceTypes = $this->searchFragranceTypeAction->handle($request, $this->apiRelation);

            return FragranceTypeApiResource::collection($fragranceTypes);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFragranceTypeApiRequest $request)
    {
        try {
            $fragranceType = $this->createFragranceTypeAction->handle($request);

            return new FragranceTypeApiResource($fragranceType);
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
            $fragranceType = $this->fragranceTypeService->get($id);

            return new FragranceTypeApiResource($fragranceType);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFragranceTypeApiRequest $request, string $id)
    {
        try {
            $fragranceType = $this->updateFragranceTypeAction->handle($request, $id);

            return new FragranceTypeApiResource($fragranceType);
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
            $fragranceTypeName = $this->deleteFragranceTypeAction->handle($id);

            return response()->json($fragranceTypeName);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

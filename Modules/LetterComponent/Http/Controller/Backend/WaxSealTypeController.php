<?php

namespace Modules\LetterComponent\Http\Controller\Backend;

use App\Enums\FlagType;
use App\Http\Controllers\Controller;
use App\Models\WaxSealType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\LetterComponent\Action\WaxSealType\CreateWaxSealTypeAction;
use Modules\LetterComponent\Action\WaxSealType\DeleteWaxSealTypeAction;
use Modules\LetterComponent\Action\WaxSealType\SearchWaxSealTypeAction;
use Modules\LetterComponent\Action\WaxSealType\UpdateWaxSealTypeAction;
use Modules\LetterComponent\Contract\WaxSealTypeServiceInterface;
use Modules\LetterComponent\Http\Request\Backend\WaxSealType\StoreWaxSealTypeRequest;
use Modules\LetterComponent\Http\Request\Backend\WaxSealType\UpdateWaxSealTypeRequest;
use Modules\LetterComponent\Http\Resource\Backend\WaxSealTypeBackendResource;
use Throwable;

class WaxSealTypeController extends Controller
{
    public function __construct(
        protected SearchWaxSealTypeAction $searchWaxSealTypeAction,
        protected CreateWaxSealTypeAction $createWaxSealTypeAction,
        protected UpdateWaxSealTypeAction $updateWaxSealTypeAction,
        protected DeleteWaxSealTypeAction $deleteWaxSealTypeAction,
        protected WaxSealTypeServiceInterface $waxSealTypeService
    ) {}

    public const parentPath = 'WaxSealType';

    public const indexPath = self::parentPath . '/Index';

    public const editPath = self::parentPath . '/Edit';

    public const createPath = self::parentPath . '/Create';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $waxSealTypes = $this->searchWaxSealTypeAction->handle($request);

            return Inertia::render(self::indexPath, [
                'waxSealTypes' => WaxSealTypeBackendResource::collection($waxSealTypes)
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
    public function store(StoreWaxSealTypeRequest $request)
    {
        try {
            $this->createWaxSealTypeAction->handle($request);

            return redirectView('wax-seal-types.index', 'Wax Seal type created successfully!', FlagType::SUCCESS);
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
            $waxSealType = $this->waxSealTypeService->get($id, [WaxSealType::images]);

            return Inertia::render(self::editPath, [
                'waxSealType' => new WaxSealTypeBackendResource($waxSealType)
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWaxSealTypeRequest $request, string $id)
    {
        try {
            $this->updateWaxSealTypeAction->handle($request, $id);

            return redirectView('wax-seal-types.index', 'Wax Seal type updated successfully!', FlagType::SUCCESS);
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
            $name = $this->deleteWaxSealTypeAction->handle($id);

            return redirectView('wax-seal-types.index', "$name deleted successfully!", FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

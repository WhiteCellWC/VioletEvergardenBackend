<?php

namespace Modules\Letter\Http\Controller\Backend;

use App\Enums\FlagType;
use App\Http\Controllers\Controller;
use App\Models\LetterType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Letter\Action\LetterType\CreateLetterTypeAction;
use Modules\Letter\Action\LetterType\DeleteLetterTypeAction;
use Modules\Letter\Action\LetterType\SearchLetterTypeAction;
use Modules\Letter\Action\LetterType\UpdateLetterTypeAction;
use Modules\Letter\Contract\LetterTypeServiceInterface;
use Modules\Letter\Http\Request\Backend\LetterType\StoreLetterTypeRequest;
use Modules\Letter\Http\Request\Backend\LetterType\UpdateLetterTypeRequest;
use Modules\Letter\Http\Resource\Backend\LetterTypeBackendResource;
use Throwable;

class LetterTypeController extends Controller
{
    public function __construct(
        protected SearchLetterTypeAction $searchLetterTypeAction,
        protected CreateLetterTypeAction $createLetterTypeAction,
        protected UpdateLetterTypeAction $updateLetterTypeAction,
        protected DeleteLetterTypeAction $deleteLetterTypeAction,
        protected LetterTypeServiceInterface $letterTypeService
    ) {}

    public const parentPath = 'LetterType';

    public const indexPath = self::parentPath . '/Index';

    public const editPath = self::parentPath . '/Edit';

    public const createPath = self::parentPath . '/Create';

    protected $backendRelation = [LetterType::letterTemplates];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $letterTypes = $this->searchLetterTypeAction->handle($request, $this->backendRelation);

            return Inertia::render(self::indexPath, [
                'letterTypes' => LetterTypeBackendResource::collection($letterTypes)
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
    public function store(StoreLetterTypeRequest $request)
    {
        try {
            $this->createLetterTypeAction->handle($request);

            return redirectView('letter-types.index', 'Letter Type created successfully!', FlagType::SUCCESS);
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
            $letterType = $this->letterTypeService->get($id);

            return Inertia::render(self::editPath, [
                'letterType' => new LetterTypeBackendResource($letterType)
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLetterTypeRequest $request, string $id)
    {
        try {
            $this->updateLetterTypeAction->handle($request, $id);

            return redirectView('letter-types.index', 'Letter Type updated successfully!', FlagType::SUCCESS);
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
            $name = $this->deleteLetterTypeAction->handle($id);

            return redirectView('letter-types.index', "$name deleted successfully!", FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

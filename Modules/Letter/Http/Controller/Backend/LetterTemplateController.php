<?php

namespace Modules\Letter\Http\Controller\Backend;

use App\Enums\FlagType;
use App\Http\Controllers\Controller;
use App\Models\LetterTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Letter\Action\LetterTemplate\CreateLetterTemplateAction;
use Modules\Letter\Action\LetterTemplate\DeleteLetterTemplateAction;
use Modules\Letter\Action\LetterTemplate\SearchLetterTemplateAction;
use Modules\Letter\Action\LetterTemplate\UpdateLetterTemplateAction;
use Modules\Letter\Contract\LetterTemplateServiceInterface;
use Modules\Letter\Http\Request\Backend\LetterTemplate\StoreLetterTemplateRequest;
use Modules\Letter\Http\Request\Backend\LetterTemplate\UpdateLetterTemplateRequest;
use Modules\Letter\Http\Resource\Backend\LetterTemplateBackendResource;
use Throwable;

class LetterTemplateController extends Controller
{
    public function __construct(
        protected SearchLetterTemplateAction $searchLetterTemplateAction,
        protected CreateLetterTemplateAction $createLetterTemplateAction,
        protected UpdateLetterTemplateAction $updateLetterTemplateAction,
        protected DeleteLetterTemplateAction $deleteLetterTemplateAction,
        protected LetterTemplateServiceInterface $letterTemplateService
    ) {}

    public const parentPath = 'LetterTemplate';

    public const indexPath = self::parentPath . '/Index';

    public const editPath = self::parentPath . '/Edit';

    public const createPath = self::parentPath . '/Create';

    protected $backendRelation = [
        LetterTemplate::paperType,
        LetterTemplate::fragranceType,
        LetterTemplate::envelopeType,
        LetterTemplate::waxSealType,
        LetterTemplate::letterTypes
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $letterTemplates = $this->searchLetterTemplateAction->handle($request, $this->backendRelation);

            return Inertia::render(self::indexPath, [
                'letterTemplates' => LetterTemplateBackendResource::collection($letterTemplates)
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
    public function store(StoreLetterTemplateRequest $request)
    {
        try {
            $this->createLetterTemplateAction->handle($request);

            return redirectView('letter-templates.index', 'Letter Template created successfully!', FlagType::SUCCESS);
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
            $letterTemplate = $this->letterTemplateService->get($id, $this->backendRelation);

            return Inertia::render(self::editPath, [
                'letterTemplate' => new LetterTemplateBackendResource($letterTemplate)
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLetterTemplateRequest $request, string $id)
    {
        try {
            $this->updateLetterTemplateAction->handle($request, $id);

            return redirectView('letter-templates.index', 'Letter Template updated successfully!', FlagType::SUCCESS);
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
            $name = $this->deleteLetterTemplateAction->handle($id);

            return redirectView('letter-templates.index', "$name deleted successfully!", FlagType::SUCCESS);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

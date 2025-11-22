<?php

namespace Modules\Letter\Http\Controller\Api\V1;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EnvelopeType;
use App\Models\FragranceType;
use App\Models\LetterTemplate;
use App\Models\PaperType;
use App\Models\WaxSealType;
use Modules\Letter\Action\LetterTemplate\CreateLetterTemplateAction;
use Modules\Letter\Action\LetterTemplate\DeleteLetterTemplateAction;
use Modules\Letter\Action\LetterTemplate\SearchLetterTemplateAction;
use Modules\Letter\Action\LetterTemplate\UpdateLetterTemplateAction;
use Modules\Letter\Contract\LetterTemplateServiceInterface;
use Modules\Letter\Http\Request\Api\LetterTemplate\StoreLetterTemplateApiRequest;
use Modules\Letter\Http\Request\Api\LetterTemplate\UpdateLetterTemplateApiRequest;
use Modules\Letter\Http\Resource\Api\V1\LetterTemplateApiResource;

class LetterTemplateApiController extends Controller
{
    protected $frontendRelation = [
        LetterTemplate::paperType,
        LetterTemplate::paperType . '.' . PaperType::images,
        LetterTemplate::waxSealType,
        LetterTemplate::waxSealType . '.' . WaxSealType::images,
        LetterTemplate::fragranceType,
        LetterTemplate::fragranceType . '.' . FragranceType::images,
        LetterTemplate::envelopeType,
        LetterTemplate::envelopeType . '.' . EnvelopeType::images,
    ];

    public function __construct(
        protected SearchLetterTemplateAction $searchLetterTemplateAction,
        protected CreateLetterTemplateAction $createLetterTemplateAction,
        protected UpdateLetterTemplateAction $updateLetterTemplateAction,
        protected DeleteLetterTemplateAction $deleteLetterTemplateAction,
        protected LetterTemplateServiceInterface $letterTemplateService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $letterTemplates = $this->searchLetterTemplateAction->handle($request);

            return LetterTemplateApiResource::collection($letterTemplates);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLetterTemplateApiRequest $request)
    {
        try {
            $letterTemplate = $this->createLetterTemplateAction->handle($request);

            return new LetterTemplateApiResource($letterTemplate);
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
            $letterTemplate = $this->letterTemplateService->get($id, $this->frontendRelation);

            return new LetterTemplateApiResource($letterTemplate);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLetterTemplateApiRequest $request, string $id)
    {
        try {
            $letterTemplate = $this->updateLetterTemplateAction->handle($request, $id);

            return new LetterTemplateApiResource($letterTemplate);
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
            $letterTemplateName = $this->deleteLetterTemplateAction->handle($id);

            return response()->json($letterTemplateName);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}

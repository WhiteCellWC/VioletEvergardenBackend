<?php

namespace Modules\Letter\Action\LetterTemplate;

use Throwable;
use Illuminate\Http\Request;
use Modules\Letter\Contract\LetterTemplateServiceInterface;
use Modules\Letter\DTO\SearchLetterTemplateDto;
use Modules\Shared\DTO\QueryOptionsDto;

class SearchLetterTemplateAction
{
    public function __construct(protected LetterTemplateServiceInterface $letterTemplateService) {}

    public function handle(Request $request, array|string|null $relation = null)
    {
        try {
            $condsIn = SearchLetterTemplateDto::fromRequest($request)->toArray();

            $queryOptions = QueryOptionsDto::fromRequest($request)->toArray();

            $letterTemplates = $this->letterTemplateService->getAll($relation, $condsIn, $request->condsNotIn, $queryOptions);

            return $letterTemplates;
        } catch (Throwable $e) {
            dd($e->getMessage());
            throw $e;
        }
    }
}

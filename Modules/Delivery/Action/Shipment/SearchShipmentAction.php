<?php

namespace Modules\Delivery\Action\Shipment;

use App\Enums\SendType;
use Throwable;
use Illuminate\Http\Request;
use Modules\Delivery\DTO\SearchShipmentDto;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\Shared\DTO\QueryOptionsDto;

class SearchShipmentAction
{
    public function __construct(protected LetterServiceInterface $letterService) {}

    public function handle(Request $request, array|string|null $relation = null)
    {
        try {
            $request->merge([
                'is_draft' => 0
            ]);

            $condsIn = SearchShipmentDto::fromRequest($request)->toArray();

            $queryOptions = QueryOptionsDto::fromRequest($request)->toArray();

            $letters = $this->letterService->getAll($relation, $condsIn, $request->condsNotIn, $queryOptions);

            return $letters;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}

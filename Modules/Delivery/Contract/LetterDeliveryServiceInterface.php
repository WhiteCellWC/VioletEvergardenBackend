<?php

namespace Modules\Delivery\Contract;

use App\Models\LetterDelivery;
use App\Models\Recipient;
use Modules\Delivery\DTO\LetterDeliveryDto;

interface LetterDeliveryServiceInterface
{
    public function get(string $id, string|array|null $relation = null);

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null);

    public function create(LetterDeliveryDto $letterTemplateDto);

    public function update(LetterDeliveryDto $letterTemplateDto);

    public function delete(string|LetterDelivery $id);
}

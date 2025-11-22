<?php

namespace Modules\Delivery\Contract;

use App\Models\DeliveryOption;
use Modules\Delivery\DTO\DeliveryOptionDto;

interface DeliveryOptionServiceInterface
{
    public function get(string $id, string|array|null $relation = null);

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null);

    public function create(DeliveryOptionDto $letterTemplateDto);

    public function update(DeliveryOptionDto $letterTemplateDto);

    public function delete(string|DeliveryOption $id);
}

<?php

namespace Modules\Delivery\Contract;

use App\Models\DeliveryTier;
use Modules\Delivery\DTO\DeliveryTierDto;

interface DeliveryTierServiceInterface
{
    public function get(string $id, string|array|null $relation = null);

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null);

    public function create(DeliveryTierDto $letterTemplateDto);

    public function update(DeliveryTierDto $letterTemplateDto);

    public function delete(string|DeliveryTier $id);
}

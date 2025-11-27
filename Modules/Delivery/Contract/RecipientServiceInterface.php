<?php

namespace Modules\Delivery\Contract;

use App\Models\Recipient;
use Modules\Delivery\DTO\RecipientDto;

interface RecipientServiceInterface
{
    public function get(string $id, string|array|null $relation = null);

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null);

    public function create(RecipientDto $letterTemplateDto);

    public function update(RecipientDto $letterTemplateDto);

    public function delete(string|Recipient $id);
}

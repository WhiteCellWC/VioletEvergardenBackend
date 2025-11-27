<?php

namespace Modules\Letter\Contract;

use App\Models\Letter;
use Modules\Letter\DTO\LetterDto;

interface LetterServiceInterface
{
    public function get(string $id, string|array|null $relation = null);

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null);

    public function create(LetterDto $letterDto);

    public function update(LetterDto $letterDto);

    public function delete(string|Letter $id);
}

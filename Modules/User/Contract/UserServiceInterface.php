<?php

namespace Modules\User\Contract;

use Modules\User\DTO\UserDto;

interface UserServiceInterface
{
    public function get(string $id, string|array|null $relation = null);

    public function getAll(array|string|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null);

    public function create(UserDto $countryDto);

    public function update(UserDto $countryDto);

    public function delete(string $id);
}

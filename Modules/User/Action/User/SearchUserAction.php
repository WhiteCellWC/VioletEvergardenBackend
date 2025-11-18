<?php

namespace Modules\User\Action\User;

use Illuminate\Http\Request;
use Modules\Shared\DTO\QueryOptionsDto;
use Modules\User\Contract\UserServiceInterface;
use Modules\User\DTO\SearchUserDto;
use Throwable;

class SearchUserAction
{
    public function __construct(protected UserServiceInterface $userService) {}

    public function handle(Request $request, array|string|null $relation = null)
    {
        try {
            $condsIn = SearchUserDto::fromRequest($request)->toArray();

            $queryOptions = QueryOptionsDto::fromRequest($request)->toArray();

            /**
             * @todo condsNotIn need to be implmented
             */
            $users = $this->userService->getAll($relation, $condsIn, [], $queryOptions);

            return $users;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}

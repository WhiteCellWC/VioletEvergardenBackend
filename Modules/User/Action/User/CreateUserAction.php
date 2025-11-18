<?php

namespace Modules\User\Action\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\User\Contract\UserServiceInterface;
use Modules\User\DTO\UserDto;
use Modules\User\Http\Cache\UserCache;
use Throwable;

class CreateUserAction
{
    public function __construct(protected UserServiceInterface $userService) {}

    public function handle(Request $request)
    {
        DB::beginTransaction();
        try {
            $userDto = UserDto::fromRequest($request);

            $user = $this->userService->create($userDto);

            Cache::tags([UserCache::GET_ALL])->flush();
            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

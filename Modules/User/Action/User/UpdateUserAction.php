<?php

namespace Modules\User\Action\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\User\Contract\UserServiceInterface;
use Modules\User\DTO\UserDto;
use Modules\User\Http\Cache\UserCache;
use Throwable;

class UpdateUserAction
{
    public function __construct(protected UserServiceInterface $userService) {}

    public function handle(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $userDto = UserDto::fromRequest($request, $id);

            $user = $this->userService->update($userDto);

            Cache::tags([UserCache::GET_ALL, UserCache::GET . '_' . $userDto->id])->flush();
            DB::commit();

            return $user;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

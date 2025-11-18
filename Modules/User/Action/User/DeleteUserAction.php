<?php

namespace Modules\User\Action\User;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\User\Contract\UserServiceInterface;
use Modules\User\Http\Cache\UserCache;
use Throwable;

class DeleteUserAction
{
    public function __construct(protected UserServiceInterface $userService) {}

    public function handle(string $id)
    {
        DB::beginTransaction();
        try {
            $userName = $this->userService->delete($id);

            Cache::tags([UserCache::GET_ALL, UserCache::GET . '_' . $id])->flush();
            DB::commit();

            return $userName;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

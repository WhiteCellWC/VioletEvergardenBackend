<?php

namespace Modules\User\Http\Service;

use App\Http\Service\BaseService;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\User\Contract\UserServiceInterface;
use Modules\User\DTO\UserDto;
use Modules\User\Http\Cache\UserCache;

class UserService extends BaseService implements UserServiceInterface
{
    public function get(string $id, array|string|null $relation = null)
    {
        try {
            $cacheKey = UserCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([UserCache::GET, UserCache::GET . "_" . $id])->remember(
                $cacheKey,
                UserCache::GET_EXPIRY,
                fn() => User::when(
                    $id,
                    fn($query, $id) => $query->where(User::id, $id)
                )->when(
                    $relation,
                    fn($query, $relation) => $query->with($relation)
                )->first()
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAll(array|string|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null)
    {
        try {
            $cacheKey = UserCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions'   => $queryOptions,
                'routeType' => request()->is('api/*') ? 'api' : 'web'
            ]));

            return Cache::tags([UserCache::GET_ALL])->remember(
                $cacheKey,
                UserCache::GET_ALL_EXPIRY,
                fn() => $this->fetch(
                    User::when(
                        $relation,
                        fn($query, $relation) => $query->with($relation)
                    )->when(
                        $condsIn,
                        fn($query, $condsIn) => $query->condsInByColumns($condsIn)
                    )->when(
                        $condsNotIn,
                        fn($query, $condsNotIn) => $query->condsNotInByColumns($condsNotIn)
                    )->when(
                        $queryOptions,
                        fn($query, $queryOptions) => $query->queryOptions($queryOptions)
                    ),
                    $queryOptions
                )
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create(UserDto $userDto)
    {
        try {
            return User::create($userDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(UserDto $userDto)
    {
        try {
            $user = $this->get($userDto->id);
            $user->fill($userDto->toArray());
            $user->save();

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string $id)
    {
        try {
            $user = $this->get($id);
            $name = $user->{User::name};
            $user->delete();

            return $name;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

<?php

namespace Modules\LetterComponent\Http\Service;

use App\Http\Service\BaseService;
use App\Models\WaxSealType;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\LetterComponent\Contract\WaxSealTypeServiceInterface;
use Modules\LetterComponent\DTO\WaxSealTypeDto;
use Modules\LetterComponent\Http\Cache\WaxSealTypeCache;

class WaxSealTypeService extends BaseService implements WaxSealTypeServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = WaxSealTypeCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([WaxSealTypeCache::GET, WaxSealTypeCache::GET . "_" . $id])->remember(
                $cacheKey,
                WaxSealTypeCache::GET_EXPIRY,
                fn() => WaxSealType::when(
                    $id,
                    fn($query, $id) => $query->where(WaxSealType::id, $id)
                )->when(
                    $relation,
                    fn($query, $relation) => $query->with($relation)
                )->first()
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null)
    {
        try {
            $cacheKey = WaxSealTypeCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([WaxSealTypeCache::GET_ALL])->remember(
                $cacheKey,
                WaxSealTypeCache::GET_EXPIRY,
                fn() => $this->fetch(
                    WaxSealType::when(
                        $condsIn,
                        fn($query, $condsIn) => $query->condsInByColumns($condsIn)
                    )->when(
                        $condsNotIn,
                        fn($query, $condsNotIn) => $query->condsNotInByColumns($condsNotIn)
                    )->when(
                        $queryOptions,
                        fn($query, $queryOptions) => $query->queryOptions($queryOptions)
                    )->when(
                        $relation,
                        fn($query, $relation) => $query->with($relation)
                    ),
                    $queryOptions
                )
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create(WaxSealTypeDto $waxSealTypeDto)
    {
        try {
            return WaxSealType::create($waxSealTypeDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(WaxSealTypeDto $waxSealTypeDto)
    {
        try {
            $waxSealType = $this->get($waxSealTypeDto->id);
            $waxSealType->fill($waxSealTypeDto->toArray());
            $waxSealType->save();

            return $waxSealType;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|WaxSealType $id)
    {
        try {
            $waxSealType = $id instanceof WaxSealType ? $id : $this->get($id);
            $name = $waxSealType->{WaxSealType::name};
            $waxSealType->delete();

            return $name;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

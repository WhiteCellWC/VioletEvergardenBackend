<?php

namespace Modules\LetterComponent\Http\Service;

use App\Http\Service\BaseService;
use App\Models\PaperType;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\LetterComponent\Contract\PaperTypeServiceInterface;
use Modules\LetterComponent\DTO\PaperTypeDto;
use Modules\LetterComponent\Http\Cache\PaperTypeCache;

class PaperTypeService extends BaseService implements PaperTypeServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = PaperTypeCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([PaperTypeCache::GET, PaperTypeCache::GET . "_" . $id])->remember(
                $cacheKey,
                PaperTypeCache::GET_EXPIRY,
                fn() => PaperType::when(
                    $id,
                    fn($query, $id) => $query->where(PaperType::id, $id)
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
            $cacheKey = PaperTypeCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([PaperTypeCache::GET_ALL])->remember(
                $cacheKey,
                PaperTypeCache::GET_EXPIRY,
                fn() => $this->fetch(
                    PaperType::when(
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

    public function create(PaperTypeDto $paperTypeDto)
    {
        try {
            return PaperType::create($paperTypeDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(PaperTypeDto $paperTypeDto)
    {
        try {
            $paperType = $this->get($paperTypeDto->id);
            $paperType->fill($paperTypeDto->toArray());
            $paperType->save();

            return $paperType;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|PaperType $id)
    {
        try {
            $paperType = $id instanceof PaperType ? $id : $this->get($id);
            $name = $paperType->{PaperType::name};
            $paperType->delete();

            return $name;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

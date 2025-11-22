<?php

namespace Modules\LetterComponent\Http\Service;

use App\Http\Service\BaseService;
use App\Models\FragranceType;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\LetterComponent\Contract\FragranceTypeServiceInterface;
use Modules\LetterComponent\DTO\FragranceTypeDto;
use Modules\LetterComponent\Http\Cache\FragranceTypeCache;

class FragranceTypeService extends BaseService implements FragranceTypeServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        $cacheKey = FragranceTypeCache::GET . '_' . $id . ':' . md5(json_encode([
            'relation' => $relation
        ]));
        try {
            return Cache::tags([FragranceTypeCache::GET, FragranceTypeCache::GET . "_" . $id])->remember(
                $cacheKey,
                FragranceTypeCache::GET_EXPIRY,
                fn() => FragranceType::query()
                    ->when(
                        $relation,
                        fn($query, $relation) => $query->with($relation)
                    )->findOrFail($id)
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAll(string|array|null $relation = null, ?array $condsIn = null, ?array $condsNotIn = null, ?array $queryOptions = null)
    {
        try {
            $cacheKey = FragranceTypeCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([FragranceTypeCache::GET_ALL])->remember(
                $cacheKey,
                FragranceTypeCache::GET_EXPIRY,
                fn() => $this->fetch(
                    FragranceType::when(
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

    public function create(FragranceTypeDto $fragranceTypeDto)
    {
        try {
            return FragranceType::create($fragranceTypeDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(FragranceTypeDto $fragranceTypeDto)
    {
        try {
            $fragranceType = $this->get($fragranceTypeDto->id);
            $fragranceType->fill($fragranceTypeDto->toArray());
            $fragranceType->save();

            return $fragranceType;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|FragranceType $id)
    {
        try {
            $fragranceType = $id instanceof FragranceType ? $id : $this->get($id);
            $name = $fragranceType->{FragranceType::name};
            $fragranceType->delete();

            return $name;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

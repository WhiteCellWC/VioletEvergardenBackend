<?php

namespace Modules\Letter\Http\Service;

use App\Http\Service\BaseService;
use App\Models\LetterType;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Letter\Contract\LetterTypeServiceInterface;
use Modules\Letter\DTO\LetterTypeDto;
use Modules\Letter\Http\Cache\LetterTypeCache;

class LetterTypeService extends BaseService implements LetterTypeServiceInterface
{
    public function get(string $id, string|array|null $relation = null)
    {
        try {
            $cacheKey = LetterTypeCache::GET . '_' . $id . ':' . md5(json_encode([
                'relation' => $relation
            ]));
            return Cache::tags([LetterTypeCache::GET, LetterTypeCache::GET . "_" . $id])->remember(
                $cacheKey,
                LetterTypeCache::GET_EXPIRY,
                fn() => LetterType::query()
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
            $cacheKey = LetterTypeCache::GET_ALL . ':' . md5(json_encode([
                'relation' => $relation,
                'condsIn'   => $condsIn,
                'condsNotIn' => $condsNotIn,
                'queryOptions' => $queryOptions
            ]));

            return Cache::tags([LetterTypeCache::GET_ALL])->remember(
                $cacheKey,
                LetterTypeCache::GET_EXPIRY,
                fn() => $this->fetch(
                    LetterType::when(
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

    public function create(LetterTypeDto $letterTypeDto)
    {
        try {
            return LetterType::create($letterTypeDto->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(LetterTypeDto $letterTypeDto)
    {
        try {
            $letterType = $this->get($letterTypeDto->id);
            $letterType->fill($letterTypeDto->toArray());
            $letterType->save();

            return $letterType;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(string|LetterType $id)
    {
        try {
            $letterType = $id instanceof LetterType ? $id : $this->get($id);
            $title = $letterType->{LetterType::name};
            $letterType->delete();

            return $title;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function attachLetterTypes(Model $model, string $letterTypeRelationName, array|Collection|int $ids)
    {
        try {
            return $model->{$letterTypeRelationName}()->sync($ids);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function detachLetterTypes(Model $model, string $letterTypeRelationName)
    {
        try {
            return $model->{$letterTypeRelationName}()->detach();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
